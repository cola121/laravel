var app = getApp()

Page({
    data: {
        back: '',    // 支持回退url
        tabUrl: [],
        showPassword: true,
        sendStatus: false,
        username: '',
        password: '',
        eyeType: 'u-icon-login-close-eye'
    },

    onShow: function () {
        try {
            var res = wx.getStorageInfoSync()
            console.log(res.keys)
            console.log(res.currentSize)
            console.log(res.limitSize)
        } catch (e) {
        }

        // 微信自动登录
        app.funcUtil.wxLogin();

        // 判断是否登录逻辑
        if (app.isLogin()) {
            console.log('已登录');
            wx.switchTab({ url: '/pages/usercenter/index/index' });
            return false;
        }
    },

    onLoad: function (options) {

        console.log(options);

        if (typeof options.back != 'undefined') {
            this.setData({back: options.back});
        }

        // 获取SessionId
        app.funcUtil.getSessionId();
    },

    bindUsernameInput: function (e) {
        this.setData({
            username: e.detail.value
        })
    },

    bindPasswordInput: function (e) {
        this.setData({
            password: e.detail.value
        })
    },

    /**
     * 清除用户名
     */
    trimUsername: function (e) {
        this.setData({
            username: ''
        });
    },

    /**
     * 密码显示
     */
    showPassword: function (e) {
        let status = !this.data.showPassword;

        this.setData({ showPassword: status });

        if (status) {
            this.setData({ eyeType: 'u-icon-login-close-eye'});
        } else {
            this.setData({ eyeType: 'u-icon-login-open-eye' });
        }
    },

    login: function () {
        this.setData({ sendStatus: true });

        app.util.doPost(
            app.api.loginUrl,
            this.data,
            (res) => {
                if (res.data.errorCode == 0) {

                    try {
                        app.util.setStorageSync('goodjobs-userId', res.data.data.userId)
                        app.util.setStorageSync('goodjobs-token', res.data.data.token)
                    } catch (e) {
                        console.log('user info error', e);

                        app.util.setStorageSync('goodjobs-userId', res.data.data.userId)
                        app.util.setStorageSync('goodjobs-token', res.data.data.token)
                    }

                    app.util.showToast('登录成功');
                    
                    console.log('back', this.data.back);

                    try {
                        var res = wx.getStorageInfoSync()
                        console.log(res.keys)
                        console.log(res.currentSize)
                        console.log(res.limitSize)
                    } catch (e) {
                    }

                    setTimeout(() => {
                        console.log('back', this.data.back);
                        if (this.data.back == 'home') {
                            wx.switchTab({ url: '/pages/usercenter/index/index'});
                        } else if (this.data.back) {
                            wx.redirectTo({ url: this.data.back });
                        } else {
                            wx.navigateBack();
                        }
                    }, 1500)
                } else if (res.data.errorCode == 98) {

                    console.log('res', res);

                    app.util.setStorageSync('goodjobs-check-mobile', this.data.username);
                    app.util.showToast('请先绑定手机号');
                    setTimeout(() => {wx.navigateTo({ url: `../check/check?password=${this.data.password}` });}, 2000)
                } else {
                    app.util.showModal(res.data.errorMessage);
                    this.setData({ sendStatus: false });
                }

                console.log(this.data.sendStatus);
            }
        )
    }
})