let app = getApp();

// pages/login/password/password.js
Page({
    /**
     * 页面的初始数据
     */
    data: {
        sendStatus: false,
        showPassword: true,
        eyeType: 'u-icon-login-close-eye',
        checkCode: app.globalData.checkCode,
        formData: {
            mobile: '',
            verCode: ''
        },
        type: '',
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        console.log(options);
        if (typeof options.type != 'undefined') {
            wx.setNavigationBarTitle({
                title: '修改手机号'
            })

            this.setData({ type: options.type });
        } else {
            this.setData({
                password: options.password,
            })
        }
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
    * 清除用户名
    */
    trimUsername: function (e) {
        this.setData({
            'formData.mobile': ''
        });
    },


    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        let mobile = app.util.getStorageSync('goodjobs-check-mobile');

        if (mobile) {
            // this.setData({'formData.mobile': mobile});
        }
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 发送验证码
     */
    sendCheckCode: function () {
        app.funcUtil.sendCheckCodeSms(this, app.api.mobileCheckSmsUrl);
    },

    /**
     * change事件
     */
    bindChange: function (e) {
        app.funcUtil.bindChange(this, e);
    },

    /**
     * 密码显示
     */
    showPassword: function (e) {
        let status = !this.data.showPassword;

        this.setData({ showPassword: status });

        if (status) {
            this.setData({ eyeType: 'u-icon-login-close-eye' });
        } else {
            this.setData({ eyeType: 'u-icon-login-open-eye' });
        }
    },

    /**
     * 修改密码
     */
    mobileCheck: function (e) {
        if (this.data.formData.mobile == '') {
            app.util.showModal('请先输入手机号')
            return false;
        }

        this.setData({ sendStatus: true });
        app.util.doPost(
            app.api.mobileCheckUrl,
            this.data.formData,
            (res) => {
                console.log('res', res);
                if (res.data.errorCode == 0) {

                    if (this.data.type == 'update') {
                        app.util.showToast('修改成功');
                        wx.navigateBack();
                        return false;
                    }

                    app.util.doPost(
                        app.api.loginUrl,
                        {
                            username: this.data.formData.mobile,
                            password: this.data.password
                        },
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

                                app.util.showToast('绑定成功');

                                this.setData({ sendStatus: false });

                            } else if (res.data.errorCode == 98) {

                                console.log('res', res);

                                app.util.showToast('绑定失败，请先重新绑定');
                                setTimeout(() => {
                                    wx.navigateTo({ url: `../check/check?password=${this.data.password}` });
                                    this.setData({ sendStatus: false });
                                 }, 2000)
                            } else {
                                app.util.showModal(res.data.errorMessage);
                                this.setData({ sendStatus: false });
                            }

                        }
                    )

                    setTimeout(() => { wx.switchTab({ url: '../../usercenter/index/index' }) }, 1000)
                } else {
                    app.util.showModal(res.data.errorMessage)
                    this.setData({ sendStatus: false })
                }
            }
        )
    }
})