const AUTH_ERROR = 57001;

/**
 * 保留当前页,并跳转到指定页面(但最多只能5层)
 */
function navigateTo(url) {
    wx.navigateTo({
        url: url
    })
}

/**
 * 关闭当前页,并跳转到指定页面
 */
function redirectTo(url) {
    wx.redirectTo({
        url: url
    })
}

/**
 * 只能用于跳转到tabBar页面，并关闭其它所有非tabBar页面
 */
function switchTab(url) {
    wx.switchTab({
        url: url
    })
}

/**
 * 异步数据存储setStorage
 */
function setStorage(key, value) {
    wx.setStorage({
        key: key,
        data: value,

        success: function (res) {
        },

        fail: function () {
        },

        complete: function () {
        }
    })
}

/**
 * 同步数据存储setStorageSync
 */
function setStorageSync(key, value) {
    wx.setStorageSync(key, value)
}

/**
 * 同步获取缓存中的内容
 */
function getStorageSync(key) {
    try {
        var result = wx.getStorageSync(key)
    } catch (e) {
        var result = null
    }
    return result
}

function previewImage(currentUrl, urlList) {
    let urls = []
    for (let url of urlList) {
        urls.push(url)
    }

    wx.previewImage({
        current: currentUrl,
        urls: urls
    })
}

function showModal(content, title = '', showCancel = false, successCallback = null, failCallback = null, completeCallback = null) {
    wx.showModal({
        title: title,
        content: content,
        showCancel: showCancel,
        success: function (res) {
            typeof successCallback == 'function' && successCallback(res)
        },
        fail: function (res) {
            typeof failCallback == 'function' && failCallback(res)
        },
        completeCallback: function (res) {
            typeof completeCallback == 'function' && completeCallback(res)
        }
    })
}

function showToast(title, icon = 'success', duration = 1000) {
    wx.showToast({
        title: title,
        icon: icon,
        duration: duration
    })
}

/**
 * 发送GET请求
 */
function doGet(url, data = {}, successCallback, failCallback, completeCallback) {

    console.log('do get url: ' + url);

    let that = this
    let header = {}
    let sid = this.getStorageSync('goodjobs-sid') || ''
    let token = this.getStorageSync('goodjobs-token') || ''

    if (sid) header.sid = sid
    if (token) header.token = token

    wx.showNavigationBarLoading();

    wx.request({
        url: url,
        data: data,
        header: header,
        success: function (res) {
            console.log('doGet url ' + url + ' success .....')

            typeof successCallback == 'function' && successCallback(res)
        },
        fail: function (res) {
            console.error('doGet url ' + url + ' fail .....')
            typeof failCallback == 'function' && failCallback(res)
        },
        complete: function (res) {
            // 鉴权失败
            if (res.data.errorCode == AUTH_ERROR) {
                app.util.setStorageSync('goodjobs-userId', NULL);
                app.util.setStorageSync('goodjobs-token', NULL);

                app.util.showToast('鉴权失败')
                setTimeout(() => { wx.redirectTo({ url: '../pages/login/login/login' }) }, 1000);

                return false;
            }

            //wx.hideToast()
            typeof completeCallback == 'function' && completeCallback(res)

            wx.hideNavigationBarLoading()
        }
    })
}

/**
 * 发送POST请求
 */
function doPost(url, data, successCallback, failCallback, completeCallback) {
    let that = this
    let sid = this.getStorageSync('goodjobs-sid') || ''
    let token = this.getStorageSync('goodjobs-token') || ''
    let header = {
        'content-type': 'application/x-www-form-urlencoded',
    }

    if (sid) header.sid = sid
    if (token) header.token = token

    wx.showNavigationBarLoading();

    wx.request({
        url: url,
        data: data,
        header: header,
        method: 'POST',
        success: function (res) {
            typeof successCallback == 'function' && successCallback(res)
        },
        fail: function (res) {
            typeof failCallback == 'function' && failCallback(res)
        },
        complete: function (res) {
            // 鉴权失败
            if (res.data.errorCode == AUTH_ERROR) {
                app.util.setStorageSync('goodjobs-userId', NULL);
                app.util.setStorageSync('goodjobs-token', NULL);

                app.util.showToast('鉴权失败')
                setTimeout(() => { wx.redirectTo({ url: '../pages/login/login/login' }) }, 1000);

                return false;
            }

            typeof completeCallback == 'function' && completeCallback(res)

            wx.hideNavigationBarLoading()
        }
    })
}

/**
 * 上传图片
 */
function doUpload(url, file, name, successCallback) {
    wx.showToast({
        title: '正在上传',
        icon: 'loading',
        duration: 10000
    })
    let sid = this.getStorageSync('goodjobs-sid') || ''
    let token = this.getStorageSync('goodjobs-token') || ''
    let header = {
        'content-type': 'application/x-www-form-urlencoded',
    }

    if (sid) header.sid = sid
    if (token) header.token = token
    wx.uploadFile({
        url: url,
        filePath: file,
        name: name,
        formData: {
        },
        header: header,
        success: function (res) {
            typeof successCallback == 'function' && successCallback(res)
        }
    })
}

/**
 * 获取格式化日期(年-月)
 */
function formatDate(date) {
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()
    return [year, month].map(formatNumber).join('-')
}

/**
 * 获取当前年份
 */
function getYear() {
    var date = new Date();
    return date.getFullYear();
}

/**
 * 格式化数字
 */
function formatNumber(n) {
    n = n.toString()
    return n[1] ? n : '0' + n
}


/**
 * toast提示
 */
function showCustomToast(_this, text, count=2000) {
    // 显示toast  
    _this.setData({
        count: count,
        toastText: text,
        isShowToast: true,
    });
    clearTimeout()

    if (count) {
        // 定时器关闭  
        setTimeout(function () {
            _this.setData({
                isShowToast: false
            });
        }, _this.data.count);
    }
}


module.exports = {
    navigateTo: navigateTo,
    redirectTo: redirectTo,
    switchTab: switchTab,
    doGet: doGet,
    doPost: doPost,
    doUpload: doUpload,
    setStorage: setStorage,
    setStorageSync: setStorageSync,
    getStorageSync: getStorageSync,
    showModal: showModal,
    showToast: showToast,
    previewImage: previewImage,
    formatDate: formatDate,
    getYear: getYear,
    showCustomToast: showCustomToast
}


