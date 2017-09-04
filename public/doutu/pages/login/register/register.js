let app = getApp();

// pages/login/register/register.js
let page = Page({
    /**
     * 页面的初始数据
     */
    data: {
        showPassword: true,
        sendStatus: false,
        eyeType: 'u-icon-login-close-eye',
        checkCode: app.globalData.checkCode,
        isRegister: false,
        isTyped: false,
        formData: {
            mobile: '',
            smsCodeStr: '',
            passwd: '',
            userID: ''
        }
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        // 获取SessionId
        app.funcUtil.getSessionId();
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {

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
    * 清除用户名
    */
    trimUsername: function (e) {
        this.setData({
            'formData.mobile': ''
        });
    },

    /**
     * 发送验证码
     */
    sendCheckCode: function () {
        if (this.data.formData.mobile == '') {
            app.util.showModal('请先输入手机号');
            return false;
        }

        // if (!this.fiveMonthCheck()) return false;
        app.funcUtil.sendCheckCodeSms(this, app.api.registerSmsUrl);
    },

    /**
     * change事件
     */
    bindChange: function (e) {
        app.funcUtil.bindChange(this, e);
    },

    /**
     * 注册 五个月时间判断
     */
    fiveMonthCheck: function() {
        console.log('five', this.data.isRegister);
        if (!this.data.isRegister && this.data.isTyped != false) {
            wx.showModal({
                title: '',
                confirmText: '直接登录',
                content: '您的手机号已被注册您可以直接登录',
                success: function (modalRes) {
                    if (modalRes.confirm) {
                        wx.navigateTo({ url: '../login/login?back=home' });
                    } else if (modalRes.cancel) {
                    }
                }
            });

            return false;
        } else {
            return true;
        }
    },

    bindMobileCheck: function(e) {
        let mobile = e.detail.value;
        console.log('bindCheck', mobile);

        app.util.doPost(
            app.api.registerUrl,
            {
                mobile: mobile,
                type: 1
            },
            (res) => {
                let that = this;

                if (res.data.errorCode == 0) {
                    console.log('register', res.data.data.data.isRegister);

                    if (typeof res.data.data.data.isRegister == 'undefined' || res.data.data.data.isRegister == null) {
                        that.setData({ isRegister: true });
                        return false;
                    }

                    // 五个月之外
                    if (res.data.data.data.isRegister == 1) {
                        wx.showModal({
                            title: '',
                            cancelText: '继续注册',
                            confirmText: '直接登录',
                            content: `您的手机号注册的账号已被以下简历使用，请确认是否是您的：\n简历姓名：${res.data.data.data.realName} \n 最后更新时间：${res.data.data.data.dateUpdate}`,
                            success: function (modalRes) {
                                if (modalRes.confirm) {
                                    wx.navigateTo({ url: '../login/login?back=home' });
                                } else if (modalRes.cancel) {
                                }
                            }
                        });

                        that.setData({ isRegister: true, 'formData.userID': res.data.data.data.userID });
                    } else if (res.data.data.data.isRegister == 0) {
                        wx.showModal({
                            title: '',
                            confirmText: '直接登录',
                            content: '您的手机号已被注册您可以直接登录',
                            success: function (modalRes) {
                                if (modalRes.confirm) {
                                    console.log('用户点击确定');
                                    wx.navigateTo({ url: '../login/login?back=home' });
                                } else if (modalRes.cancel) {
                                }
                            }
                        })
                    } else {
                        that.setData({ isRegister: true });
                    }

                } else {
                    app.util.showModal(res.data.errorMessage)
                }
            }
        )

        console.log(this.data.isRegister);
    },

    /**
     * mobile change
     */
    bindMobileChange: function(e) {
        this.setData({ isRegister: false, isTyped: true});
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
     * 注册
     */
    register: function () {
        // if(!this.fiveMonthCheck()) return false;

        console.log('codeStr', this.data);

        if (this.data.formData.mobile == '') {
            app.util.showModal('请先输入手机号')
            return false;
        }

        if (this.data.formData.smsCodeStr == '') {
            app.util.showModal('请输入验证码');
            return false;
        }

        console.log(this.data.formData);

        this.setData({ sendStatus: true });
        app.util.doPost(
            app.api.registerUrl,
            this.data.formData,
            (res) => {
                if (res.data.errorCode == 0) {
                    app.util.setStorageSync('goodjobs-userId', res.data.data.userId)
                    app.util.setStorageSync('goodjobs-token', res.data.data.token)
                    app.util.showToast('注册成功')
                    setTimeout(() => { wx.switchTab({ url: '../../usercenter/index/index' }) }, 1000)
                } else {
                    app.util.showModal(res.data.errorMessage)
                }

                this.setData({ sendStatus: false });
            }
        )
    }
})