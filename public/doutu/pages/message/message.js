// pages/message/message.js
var app = getApp();
Page({
    data: {
        url: {
            apply: '/pages/usercenter/job-apply-list/index',
            invitation: '/pages/usercenter/invite/index',
            visit: '/pages/usercenter/corp-view-history/index'
        }
    },

    // 页面显示
    onShow: function () {
        app.funcUtil.parseUrl(this);
        console.log(this.data.url);
        this.getList();
    },
    onLoad: function (options) {
    },
    onReady: function () {
        // 页面渲染完成
    },
    onHide: function () {
        // 页面隐藏
    },
    onUnload: function () {
        // 页面关闭
    },
    /**
     * 获取记录数
     */
    getList:function() {
        app.util.doPost(
            app.api.getMessageCountUrl,
            {},
            (res) => {
                this.setData({
                    mailCount: typeof res.data.data != 'undefined' ? res.data.data.mailCount : 0,
                    inviteCount: typeof res.data.data != 'undefined' ? res.data.data.inviteCount : 0,
                    viewHistoryCount: typeof res.data.data != 'undefined' ? res.data.data.viewHistoryCount : 0,
                })
            }
        )
    },
    //下拉刷新
    onPullDownRefresh: function () {
        this.getList();
        wx.stopPullDownRefresh();
    },
})