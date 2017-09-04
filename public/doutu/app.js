App({
    data: {
    },

    onLaunch: function () {
        this.updateMetaData();
    },

    onShow: function () {
        try {
            var res = wx.getStorageInfoSync()
            console.log(res.keys)
            console.log(res.currentSize)
            console.log(res.limitSize)
        } catch (e) {
        }
    },

    isLogin: function () {
        if (wx.getStorageSync('goodjobs-userId')) return true
        return false
    },
 
    // 检测Login 未登录直接跳转到登陆页
    checkLogin: function () {
        if (!this.isLogin()) {
            this.util.navigateTo('/pages/login/login/login')
            return false;
        } else {
            return true;
        }
    },

    api: require('/config/url.js'),
    util: require('/utils/util.js'),
    funcUtil: require('/utils/funcUtil.js'),

    /**
     * 扫码逻辑
     */
    qrCodeHandel: function(scene) {
        if (typeof scene == 'undefined') {
            return false;
        }
        
        // 形如 "isJob,15521" "isCrop,12231" 
        let qrString = scene.split(',');

        // 职位详情 820205
        let url = '/pages/jobs/job-info/job-info?viewType='+ qrString[0] +'&jobID=' + parseInt(qrString[1]);

        console.log('test qrcode');
        console.log('scene', scene);
        console.log('url', url);

        this.util.navigateTo(url);
    },

    /**
     * 获取当前时间
     */
    getNowDate: function () {
        let date = new Date();

        return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
    },

    /**
     * 从接口获取数据并缓存
     */
    updateMetaData: function () {
        let that = this;

        that.util.doGet(that.api.commonMetaUrl, [], function (res) {
            if (res.data.data && typeof res.data.data == 'object' && Object.keys(res.data.data).length > 0) {
                console.log('saved');

                try {
                    wx.removeStorageSync('goodjobs-meta-data');
                    that.util.setStorageSync('goodjobs-meta-data', JSON.stringify(res.data.data));
                } catch (e) {
                    wx.removeStorageSync('goodjobs-meta-data');
                    that.util.setStorageSync('goodjobs-meta-data', JSON.stringify(res.data.data));
                    
                    console.log('updateMetaDataError', e);
                }
            } else {

                console.log('no saved');
            }
        }, function(res) {
            that.util.showToast('网络异常');
        });
    },

    /**
     * 获取Meta数据 注：app生命周期开始 也就是在app.js中不要调用
     */
    getMetaData: function () {
        let jsonMetaData = this.util.getStorageSync('goodjobs-meta-data');

        console.log("before parser");

        // console.log(jsonMetaData);

        let metaData = {};

        try {
            metaData = JSON.parse(jsonMetaData);
        } catch (e) {
            console.log("error form jsonParse \n");
            console.log(e);
            console.log(metaData);
            console.log('jsonmetaData', jsonMetaData);
        }

        console.log("parsed, length" + Object.keys(metaData).length);

        if (Object.keys(metaData).length < 1) {
            // this.getMetaData();
            console.log('net error retry');
            // return false;
        }

        // 增加工作年份
        metaData.workYear = this.funcUtil.getWorkYear();

        return metaData;
    },

    globalData: {
        checkCode: {
            status: false,
            timer: 60,
            text: "发送验证码"
        }
    }

})