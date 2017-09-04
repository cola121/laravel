var app = getApp()

Page({
  data: {
    cvStatusList: {
      'progressRate': 0,
    },
    portrait: '/resources/user-img.png',
    bgColor: '#efefef',
    updateTime: '',
    url: {
      cv: '/pages/usercenter/resume-integrity/index',
      view: '/pages/usercenter/view-resume/view-resume',
      open: '/pages/usercenter/resume-open/index'
    }
  },
  onLoad: function () {
  },
  onShow: function () {
    app.funcUtil.parseUrl(this);
    console.log(this.data.url);

    this.setData({ isLogin: app.isLogin() });
    app.funcUtil.getCvStatus('cvStatusList', this);
    this.getPortrait();
    this.getUpdateTime();
  },
  //获取头像
  getPortrait: function () {
    app.util.doPost(
      app.api.getUserCenterUrl,
      {},
      (res) => {
        if (res.data.errorCode == 0 && res.data.data.pic) {
          let pic = res.data.data.pic;
          this.setData({
            portrait: pic,
          })
        }
      }
    )
  },
  //获取简历更新时间
  getUpdateTime: function () {
    app.util.doPost(
      app.api.getUserCenterUrl,
      {},
      (res) => {
        if (res.data.errorCode == 0) {
          this.setData({
            updateTime: res.data.data.updateTime,
          })
        }
      }
    )
  },
  logout: function () {
    app.util.doGet(
      app.api.logoutUrl,
      { userId: wx.getStorageSync('goodjobs-userId')},
      (res) => {
        console.log(res);
      }
    );

    wx.removeStorageSync('goodjobs-sid')
    wx.removeStorageSync('goodjobs-userId')
    wx.removeStorageSync('goodjobs-token')
    wx.removeStorageSync('user-location');

    app.util.showToast('退出成功')
    setTimeout(function () {
      wx.reLaunch({
        url: '/pages/jobs/job/job'
      })
    }, 1000)

  },
  chooseimage: function () {
    if (!app.isLogin()) {
      app.util.navigateTo('/pages/login/login/login');
      return false;
    }

    var that = this;
    wx.chooseImage({
      success: function (res) {
        var tempFilePaths = res.tempFilePaths
        app.util.doUpload(app.api.getPhotoSaveUrl, tempFilePaths[0], 'portrait', (result) => {
          if (result.statusCode == 200) {
            var _return = JSON.parse(result.data);
            if (_return.errorCode == 0) {
              app.util.showToast(_return.data.message);
              let img_url = _return.data.photo;
              that.setData({
                portrait: img_url,
                bgColor: '#fff'
              })
            } else {
              wx.hideToast();
              app.util.showModal(_return.errorMessage);
            }
          } else {
            wx.hideToast();
            app.util.showModal("图片超过限制大小,必须在1M以内");

          }

        })

      }
    })
  },
  //刷新简历
  bindUpdateResume: function () {
    app.checkLogin();
    app.util.doGet
      (
      app.api.getUpdateResumeUrl,
      {},
      (res) => {
        if (res.data.errorCode == 0) {
          app.util.showToast(res.data.data);
          this.getUpdateTime();
        } else {
          app.util.showModal('简历刷新失败');

        }
      }
      )
  }
})