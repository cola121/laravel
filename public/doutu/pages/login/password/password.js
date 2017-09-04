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
            smsCodeStr: '',
            newPassword: ''
        }
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {

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
        app.funcUtil.sendCheckCodeSms(this, app.api.modifyPasswordSmsUrl);
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
    modifyPassword: function (e) {
        this.setData({ sendStatus: true });
        app.util.doPost(
            app.api.modifyPasswordUrl,
            this.data.formData,
            (res) => {
                if (res.data.errorCode == 0) {
                    app.util.showToast('修改成功')
                    setTimeout(() => { wx.switchTab({ url: '../../usercenter/index/index' }) }, 1000)
                } else {
                    app.util.showModal(res.data.errorMessage)
                }

                this.setData({ sendStatus: false });
            }
        )
    }
})