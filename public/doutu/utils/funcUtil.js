/* 应用层公共逻辑库 */
let util = require('./util.js');
let api = require('../config/url.js');

let funcUtil = {
    /**
     * 发送注册短息
     */
    sendCheckCodeSms: function (instance, url) {
        instance.setData({ 'checkCode.text': instance.data.checkCode.timer + "秒后重新发送" });
        instance.setData({ 'checkCode.status': true });

        // 发送短信
        util.doPost(url, instance.data.formData, '', '', (res) => {
            if (res.data.errorCode !== 0) {
                util.showModal(res.data.errorMessage);
                timeOutFunc();
            }
        });

        let timeOutFunc = () => {
            instance.setData({ 'checkCode.status': false });
            instance.setData({ 'checkCode.text': "发送验证码" });
            clearInterval(timer);
            instance.setData({ 'checkCode.timer': 60 });
        };

        let timeFunc = () => {
            instance.setData({ 'checkCode.timer': instance.data.checkCode.timer - 1 });
            instance.setData({ 'checkCode.text': instance.data.checkCode.timer + "秒后重新发送" });
        }

        // 控制发送按钮状态
        let timer = setInterval(function () {
            if (instance.data.checkCode.timer <= 1) {
                timeOutFunc();
            } else {
                timeFunc();
            }
        }, 1000);
    },

    /**
     * 获取SessionId
     */
    getSessionId: function () {
        util.doGet(
            api.getSessionIdUrl,
            {},
            (res) => {
                if (res.data.errorCode == 0) {
                    util.setStorageSync('goodjobs-sid', res.data.data.sessionId);
                }
            }
        )
    },

    /**
     * 绑定表单Change事件
     * Change事件用法:
     * data中建立一个formData 存储表单数据 如{formData: {username:"Rootrl"}} name即为具体表单数据
     * 相应Page方法中新建一个bindChange方法调用此方法
     * wxml中input 添加 bindChange="bindChange" data-name="username" value="{{formData.username}}"
     * 即可完成 input内容更改自动更新到formData中对应的一条表单数据
     */
    bindChange: function (instance, e, changeData = 'formData') {
        let attr = changeData + '.' + e.currentTarget.dataset.name;

        instance.setData({
            [attr]: e.detail.value
        });
    },

    /**
     * 处理Url 主要是检测登录 解决页面闪的问题
     */
    parseUrl: function(instance) {
        let loginUrl = '/pages/login/login/login';
        let isLogin = false;
        let url = instance.data.url;

        if (wx.getStorageSync('goodjobs-userId')) {
            isLogin = true;
            if (typeof instance.data.tmpUrl != 'undefined') {
                instance.setData({url: instance.data.tmpUrl});
            }
        } else {
            if (typeof instance.data.tmpUrl == 'undefined') {
                instance.setData({ tmpUrl: url });
            }
        }

        for (let index in url) {
            console.log(isLogin, isLogin);
            let attr = 'url.'+index;
            if (!isLogin) {
                instance.setData({ [attr]: `${loginUrl}?back=${instance.data.tmpUrl[index]}` });
            }
        }
        console.log('parseUrl Data');
        console.log( 'tmp',instance.data.tmpUrl);
        console.log('url', instance.data.url);
        console.log('isLogin', isLogin);
        console.log('userId', wx.getStorageSync('goodjobs-userId'));
    },

    /**
     * 职位申请
     */
    applyJob: function (jobID, _this) {
        util.doPost(
            api.applyJobUrl,
            { jobID: jobID },
            (res) => {
                if (res.data.errorCode == 0) {
                    util.showCustomToast(_this, res.data.data.message)
                } else {
                    // 错误提示弹窗
                    util.showCustomToast(_this, res.data.errorMessage);
                }

            }
        )
    },
    /**
     * 获取简历状态
     * ele 容器
     */
    getCvStatus: function (ele, _this) {
        util.doPost(
            api.getCvStatusUrl,
            {},
            (res) => {
                if (res.data.errorCode == 0) {
                    _this.setData({
                        [ele]: res.data.data
                    })
                }
            }
        )

    },

    /*
     * 绑定picker Change事件
     * picker Change事件用法：
     * data中建立一个formData 存储表单数据 如{formData: {sex:"男"}} name即为具体表单数据
     * data中建立一个indexData 存储索引数据 调用createIndexData方法建立formData对应indexData索引数据
     * 相应Page方法中新建一个bindPickerChange方法调用此方法
     * wxml中input 添加 bindChange = "bindPickerChange" data-name="sex" value="{{indexData.sex}}"
     */
    bindPickerChange: function (instance, e) {
        // 更新IndexData
        this.bindChange(instance, e, 'indexData');

        // 更新formData
        let name = e.currentTarget.dataset.name;
        let attr = 'formData.' + name;
        let metaName;
        let dataList = instance.data.formDataMap;

        dataList.map(function (value) {
            if (typeof value[1] !== 'undefined') {
                if (value[0] == name) {
                    metaName = value[1];

                    // 更新formData
                    instance.setData({
                        [attr]: instance.data.metaData[metaName][e.detail.value].name
                    });

                    // 更新对应code
                    if (typeof value[2] !== 'undefined') {
                        let codeAttr = 'formData.' + value[2];

                        instance.setData({
                            [codeAttr]: instance.data.metaData[metaName][e.detail.value].id
                        });
                    }
                }
            }
        });
    },

    /**
     * 创建formData对应的索引数据
     * 同时创建对应的ID数据
     */
    createIndexData: function (instance) {

        let dataList = instance.data.formDataMap;

        let _loop = function (i) {
            let data = instance.data.formData[dataList[i][0]];
            let dataIndex = '';
            let idIndex = '';

            instance.data.metaData[dataList[i][1]].map(function (value, index) {
                if (data == value.name) {
                    dataIndex = index;
                    idIndex = value.id;
                }
            });

            let attr = "indexData." + dataList[i][0];
            let idAttr = "idData." + dataList[i][0];

            instance.setData({ [attr]: dataIndex, [idAttr]: idIndex });
        }

        for (let i in dataList) {
            _loop(i);
        }
    },

    /**
     * 获取工作年份
     */
    getWorkYear: function () {
        let date = new Date();
        let startWorkYear = 1970;
        let diffYear = date.getFullYear() - startWorkYear;
        let workYearData = [];

        workYearData.push({ id: 9999, name: '无工作经验' });

        let _loop = function (i) {
            let singleData = { name: startWorkYear + i, id: startWorkYear + i };
            workYearData.push(singleData);
        }

        for (let i = diffYear; i >= 0; i--) {
            _loop(i);
        }

        return workYearData;
    },

    /**
     * 替换对象中的属性名
     * attrlist = [[['name'],'newName'], ...]
     */
    changeObjAttr: function (dataList, attrList) {
        let _loop = function (i) {
            let value = dataList[attrList[i][0]];

            if (typeof value != 'undefined') {
                delete dataList[attrList[i][0]];
                dataList[attrList[i][1]] = value;
            }
        }

        for (let i in attrList) {
            _loop(i);
        }

        return dataList;
    },

    /**
     * 获取当前页面跳转方式
     */
    getJumpType: function() {
        let pageStack = getCurrentPages();

        // 默认navigate跳转
        let jumpType = 'navigate';

        // 页面层数满五级时redirect跳转
        if (pageStack.length >= 5) {
            jumpType = 'redirect';
        }

        return jumpType;
    },

    /**
     * 执行当前页面跳转
     * 优化 工具库应提供url tabbar判断
     */
    exePageJump: function(url) {
        let pageStack = getCurrentPages();

        if (pageStack.length >= 5) {
            wx.navigateTo({url: url});
        } else {
            wx.redirectTo({url: url});
        }
    },

    /**
     * 获取Selector缓存数据
     * params: flag 缓存标识
     */
    getSelectorStorage: function (_flag) {
        let flag = 'goodjobs-selector-' + _flag;
        let jsonData = util.getStorageSync(flag);

        console.log('get', flag);

        if (!jsonData) {
            return false;
        }

        let data = JSON.parse(jsonData);

        console.log(data, jsonData);

        if (data.length <= 0) {
            return false;
        }

        // 使用后删除缓存
        wx.removeStorageSync(flag);

        return data;
    },

    // 微信登录
    wxLogin: function () {
      wx.login({
        success: function (res) {
          let code = res.code;
          // 检查自动登录
          util.doPost(
            api.checkLoginUrl,
            { code: code },
            (res) => {
              console.log(res);
              if (res.data.errorCode == 0) {
                if (!res.data.data.openid) {
                  
                  console.log('autologind');

                  try {
                    util.setStorageSync('goodjobs-userId', res.data.data.userId)
                    util.setStorageSync('goodjobs-token', res.data.data.token)
                  } catch (e) {
                    console.log('userinfo error', e);
                    util.setStorageSync('goodjobs-userId', res.data.data.userId)
                    util.setStorageSync('goodjobs-token', res.data.data.token)
                  }
                }
              }
            }
          )
        },
        complete(e) {
          console.log(e);
        }
      })
    },
};

module.exports = {
    sendCheckCodeSms: funcUtil.sendCheckCodeSms,
    getSessionId: funcUtil.getSessionId,
    bindChange: funcUtil.bindChange,
    applyJob: funcUtil.applyJob,
    getCvStatus: funcUtil.getCvStatus,
    bindPickerChange: funcUtil.bindPickerChange,
    createIndexData: funcUtil.createIndexData,
    getWorkYear: funcUtil.getWorkYear,
    changeObjAttr: funcUtil.changeObjAttr,
    getSelectorStorage: funcUtil.getSelectorStorage,
    parseUrl: funcUtil.parseUrl,
    getJumpType: funcUtil.getJumpType,
    exePageJump: funcUtil.exePageJump,
    wxLogin: funcUtil.wxLogin
};