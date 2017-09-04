var app = getApp()

Page({
    data: {
        jobcity: 1042,
        keyword: '',
        clickTime:1
    },
    onLoad: function (options) {
        // 扫码入口处理
        app.qrCodeHandel(options.scene);

        // 页面初始化 options为页面跳转所带来的参数
        if (wx.getStorageSync('user-location')) {
            var location = wx.getStorageSync('user-location')
            this.setData({
                jobcity: location.cityID
            })
            this.getJobs();
        } else {
            this.getUserLocation();
        }
    },
    onReady: function () {
        // 页面渲染完成
    },
    onShow: function () {
        this.setData({
            clickTime: 1
        })

        // 微信自动登录
        if (!app.isLogin()) {
          app.funcUtil.wxLogin();
        }
    },
    onHide: function () {
        // 页面隐藏
    },
    onUnload: function () {
        // 页面关闭
    },
    onShareAppMessage: function () {
        return {
            title: '新安人才网',
            path: '/pages/jobs/job/job',
            success: function (res) {
                // 转发成功
            },
            fail: function (res) {
                // 转发失败
            }
        }
    },
    jumpToListOne: function () {
        if (this.data.clickTime == 1) {
            wx.navigateTo({
                url: '/pages/jobs/search/search'
            })
        }
        this.setData({
            clickTime: this.data.clickTime + 1
        })
    },
    onPullDownRefresh: function () {
        this.getJobs();
    },
    getJobs: function () {
        //获取首页职位
        //获取历史搜索
        wx.showNavigationBarLoading()

        let historyArr = new Array
        var keyword = ''
        if (wx.getStorageSync('search-history')) {
            historyArr = wx.getStorageSync('search-history')
            var jobcity = this.data.jobcity
            //读取最近的搜索关键词
            for (let i = 0; i < historyArr.length; i++) {
                if (historyArr[i].keyWord) {
                    keyword = historyArr[i].keyWord
                    if (historyArr[i].cityId != jobcity) {
                        jobcity = jobcity + ',' + historyArr[i].cityId
                    }
                    break
                }
            }
            this.setData({
                keyword: keyword,
                jobcity: jobcity
            })
        } else {
            this.setData({keyword: ''})
        }
        app.util.doPost(
            app.api.jobIndexUrl,
            {
                jobcity: this.data.jobcity,
                keyword: this.data.keyword
            },
            (res) => {
                if (res.data.errorCode == 0) {
                    this.setData({
                        jobs: res.data.data.list
                    });
                    wx.stopPullDownRefresh()
                }
                wx.hideNavigationBarLoading()
            }
        )
    },

    /**
     * 获取用户位置
     */
    getUserLocation:function() {
        if (wx.getStorageSync('user-location')) {
            var location = wx.getStorageSync('user-location')
            this.setData({
                jobcity: location.cityID
            })
        } else {
            var _this = this
            wx.getLocation({
                type: 'gcj02',
                success: function (res) {
                    var latitude = res.latitude
                    var longitude = res.longitude
                    app.util.doPost(
                        app.api.getLocationUrl,
                        {
                            lat: latitude,
                            lng: longitude
                        },
                        (resb) => {
                            if (resb.data.errorCode == 0) {
                                console.log(resb)
                                app.util.setStorageSync('user-location', resb.data.data)
                                _this.setData({
                                    jobcity: resb.data.data.cityID
                                })
                                _this.getJobs()
                            } else {
                                _this.setData({
                                    jobcity: 1043
                                })
                                _this.getJobs()
                            }
                        }
                    )
                },
                fail:function(){ //定位失败
                    _this.setData({
                        jobcity: 1043
                    })
                    _this.getJobs()
                }
            })
        }
        
    }
})
