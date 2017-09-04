// pages/selector/selector.js
/**
 * 名称：Selector 一级、二级下拉组件 支持单选 多选
 * 
 * navigateTo调用 /pages/selector/selector?tree=jobloc_pro|jobloc_city&checked=1|1&num=1&flag=edu-info-major
 * 
 * 调用步骤: 表单中使用navigateTo调用 seletor点确定后 存入选择数据进localStorage中 表单页面onShow拿localsStorage拿数据
 * 
 * 具体：
 * 1，所在表单页面中设置data.flag(缓存标识) data{flag: ''} flag推荐命名为：当前页面名+当前表单字段名 如: edu-info页面中的major专业字段需要
 * 使用selector组件，则flag命名为: 'edu-info-major'
 * 2，在需要的字段绑定调用navigateTo(url) url规则为'/pages/selector/selector?tree=jobloc_pro|jobloc_city&num=1&flag=edu-info-major'
 * 3，其中 tree 为需要的两级数据 一级在前  '|'符号隔开 (如果为一级数据选择， 一级设置为"single") num为可以选择的数量 flag同上 为缓存标识
 */
let app = getApp();

Page({
    /**
     * 页面的初始数据
     */
    data: {
        // metaData
        metaData: {},
        // 可选个数
        num: 1,
        flag: '',
        currentNum: 0,
        // 需要遍历的数组
        tree: [],
        levelOne: [],
        levelTwo: [],
        levelThree: [],
        storageData: [],
        oneSelect: ''    // 二级选择时 支持一级选择
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        wx.showLoading({
            title: '数据加载中..',
        });

        let title = typeof options.title != 'undefined' ? options.title : '新安人才网';

        let oneSelect = typeof options.oneSelect != 'undefined' ? options.oneSelect : '';

        wx.setNavigationBarTitle({
            title: title
        })

        this.setData({
            tree: options.tree.split('|'),
            metaData: app.getMetaData(),
            checked: options.checked,
            num: options.num,
            flag: options.flag,
            oneSelect: options.oneSelect
        });

        /* 临时解决： 一级菜单选择 */
        this.setData({ 'metaData.ckTimeStatus.2': [{ id: 2, name: '在职不找工作' }] });

        // 清空缓存
        wx.removeStorageSync(this.data.flag);

        // 根据level获取各级数据
        let metaData = this.data.metaData;

        // 一级数据数组
        let levelOne = [{ id: 0, name: 'None' }];

        if (this.data.tree[0] != 'single') {
            levelOne = metaData[this.data.tree[0]];
        }

        // 二级数组
        let levelTwo = metaData[this.data.tree[1]];

        // 处理数据
        this.setData({ levelOne: levelOne, levelTwo: levelTwo });

        // 是否支持大类选择 不限
        this.oneSelect();
    },
    
    onReady: function() {
    },

    onShow: function () {
        // 处理默认选中
        this.checkedHandle(this.data.checked);
        wx.hideLoading();
    },

    /**
     * 二级中支持一级菜单选择 不限
     */
    oneSelect: function() {
        console.log(this.data.oneSelect);

        if (!this.data.oneSelect) {
            return false;
        }

        let dataList = this.data.levelOne;
    
        let checkedList = this.data.checked.split(';');

        let levelOneId, levelOneName, levelTwoId, levelTwoName;

        // 遍历levelOne 如果checkeList中包括levelOneID 则checked
        dataList.map((value, index) => {
            if (checkedList.indexOf(value.id.toString()) != -1) {
                dataList[index].checked = true;
                dataList[index].open = true;
                // this.setData({ currentNum: this.data.currentNum + 1 });

                // 储存数据
                let attr = "p" + value.id;

                let tmpData = {
                    levelOneId: 0,
                    levelOneName: null,
                    levelTwoId: value.id,
                    levelTwoName: value.name,
                    [attr]: value.id,
                }

                // 存储预缓存数组
                this.setStorageData(tmpData);
            }
        });

        this.setData({levelOne: dataList});
    },

    /**
     * 不限 点击事件
     */
    selectOneTab: function (e) {
        let that = this;
        let dataOne = this.data.levelOne;
        let dataTwo = this.data.levelTwo;
        let dataset = e.currentTarget.dataset;
        let levelOneId = dataset.levelOneId;
        let levelOneName = dataset.levelOneName;
        let levelTwoId = dataset.levelTwoId;
        let levelTwoName = dataset.levelTwoName;

        // 加入子类标识
        let attr = 'p' + levelOneId;

        let tmpData = {
            [attr]: levelOneId,
            levelOneId: levelOneId,
            levelOneName: levelOneName,
            levelTwoId: levelTwoId,
            levelTwoName: levelTwoName,
        }

        // 选中与解除选中处理
        dataOne.map((item, index) => {
            if (item.id != levelOneId) {
                return;
            }

            let tmpOne = dataOne[index];

            // 取消
            if (tmpOne.checked) {

                // 删除预缓存数组
                that.delStorageData(tmpData);

                // checked
                tmpOne.checked = !tmpOne.checked;

            // 选中
            } else {
                // 多选 - 判断当前选项个数
                if (that.data.currentNum >= that.data.num) {
                    app.util.showModal('当前最多可选择' + that.data.num + '项');
                    return false;
                }

                // checkbox
                tmpOne.checked = true;

                // 存储预缓存数组
                that.setStorageData(tmpData);

                // 排除同级所有子类
                let storageData = this.data.storageData;

                let saveArr = [];

                for (let i in storageData) {

                    let item = storageData[i];
                    let index = i;
                    let attr = 'c' + levelOneId;

                    if (item.hasOwnProperty(attr)) {
                        // 取消checkbox
                        dataTwo[item.levelOneId].map((itemTwo, indexTwo) => {
                            if (item.levelTwoId == itemTwo.id) {
                                dataTwo[item.levelOneId][indexTwo].checked = false;
                                this.setData({ levelTwo: dataTwo });
                            }
                        });
                    } else {
                        // 保留数组
                        saveArr.push(item);
                    }
                }

                this.setData({ storageData: saveArr });
                this.setData({ currentNum: Object.keys(this.data.storageData).length });
            }

            that.setData({ levelOne: dataOne });
        });
    },

    /**
     * 默认选中状态处理
     */
    checkedHandle: function(checked) {
        let levelOne = this.data.levelOne;
        let levelTwo = this.data.levelTwo;
        let levelOneId, levelOneName, levelTwoId, levelTwoName;

        // 没有传checked
        if (typeof checked == 'undefined') {
            return false;
        }

        // 支持二级 默认选择
        checked.split(';').map((mapValue, mapIndex) => {
            for (let i in levelTwo) {
                if (Array.isArray(levelTwo[i])) {
                    levelTwo[i].map((value, index) => {
                        // checked
                        if (value.id == mapValue) {
                            levelTwo[i][index].checked = true
                            levelTwoId = levelTwo[i][index].id;
                            levelTwoName = levelTwo[i][index].name;

                            // 父级选中
                            levelOne.map((oneValue, oneIndex) => {
                                if (i == oneValue.id) {
                                    levelOne[oneIndex].open = true;
                                    levelOneId = levelOne[oneIndex].id;
                                    levelOneName = levelOne[oneIndex].name;
                                }
                            });
                        }
                    });
                } else if (typeof levelTwo[i] == 'object') {
                    if (mapValue == levelTwo[i].id) {
                        levelOneId = 0;
                        levelOneName = null;
                        levelTwoId = levelTwo[i].id;
                        levelTwoName = levelTwo[i].name;
                        levelTwo[i].checked = true;
                    }
                }
            }

            // 加入子类标识
            let attr = 'c' + levelOneId;
            let tmpData = {
                [attr]: levelOneId,
                levelOneId: levelOneId,
                levelOneName: levelOneName,
                levelTwoId: levelTwoId,
                levelTwoName: levelTwoName,
            }

            // 存储预缓存数组
            this.setStorageData(tmpData);
        });

        this.setData({ levelOne: levelOne, levelTwo: levelTwo });
    },

    /**
     * 页面确定后 一系列事件
     */
    goBackHandle: function () {
        // 设置缓存
        this.setStorage();

        // 返回页面
        wx.navigateBack();
    },

    /**
     * 设置storage缓存
     */
    setStorage: function () {
        let storageData = this.data.storageData;
        
        // 单选情况
        if (this.data.num == 1) {
            storageData = [storageData.pop()];
        }

        if (storageData.length == 0) {
            let tmpData = {
                levelOneId: '',
                levelOneName: '',
                levelTwoId: '',
                levelTwoName: '',
            }

            this.setStorageData(tmpData);
        }

        app.util.setStorageSync('goodjobs-selector-' + this.data.flag, JSON.stringify(storageData));
    },

    /**
     * 设置storage数组
     */
    setStorageData: function (data) {
        if (typeof data.levelOneId == 'undefined') {
            return false;
        }

        let storageData = this.data.storageData;
        let length = Object.keys(storageData).length;

        if (length > 0) {
            let hasData = false;
            this.data.storageData.map((value, index) => {
                if (data.levelTwoId == value.levelTwoId) {
                    hasData = true;
                }
            });

            if (!hasData) {
                this.data.storageData.push(data);
            }
        } else {
            this.data.storageData.push(data);
        }

        // 改变当前选择数量
        this.setData({ currentNum: Object.keys(this.data.storageData).length });

        console.log(Object.keys(this.data.storageData).length);

        console.log(this.data.storageData);
    },

    /**
     * 删除storage数组
     */
    delStorageData: function (data) {
        let that = this;
        this.data.storageData.map(function (value, index) {
            if (data.levelTwoId == value.levelTwoId) {
                console.log('deleted');
                that.data.storageData.splice(index, 1);
            } else {
                console.log('no deleted');
                console.log(data);
            }
        });

        // 改变当前选择数量
        this.setData({ currentNum: Object.keys(this.data.storageData).length });

        console.log(Object.keys(this.data.storageData).length);

        console.log(this.data.storageData);
    },

    /**
     * 确定事件
     */
    bindEnter: function (e) {
        // 回显事件
        this.goBackHandle();
    },

    /**
     * level1 点击事件
     */
    itemOneTab: function (e) {
        // Toggle
        let id = e.currentTarget.dataset.id;
        let that = this;
        this.data.levelOne.map(function (value, index) {
            if (value.id == id) {
                if (that.data.levelOne[index].open) {
                    that.data.levelOne[index].open = !that.data.levelOne[index].open;
                } else {
                    that.data.levelOne[index].open = true;
                }

                that.setData({ levelOne: that.data.levelOne })
            }
        })
    },


    /**
     * level2 点击事件
     * checkbox 是否超过当前个数
     * 点击时排斥父级选择
     * 存入缓存 回退页面
     */
    itemTwoTab: function (e) {
        
        let that = this;
        let dataOne = this.data.levelOne;
        let dataset = e.currentTarget.dataset;
        let levelOneId = dataset.levelOneId;
        let levelOneName = dataset.levelOneName;
        let levelTwoId = dataset.levelTwoId;
        let levelTwoName = dataset.levelTwoName;

        // 加入子类标识
        let attr = 'c' + levelOneId;

        let tmpData = {

            [attr]: levelOneId,
            levelOneId: levelOneId,
            levelOneName: levelOneName,
            levelTwoId: levelTwoId,
            levelTwoName: levelTwoName,
        }

        // 单选 - 直接返回
        if (this.data.num == 1) {

            this.setStorageData(tmpData);

            this.goBackHandle();

            return false;
        }

        // 一级 checkbox
        let dataList = this.data.levelTwo;

        // 二级 checkbox
        if (this.data.tree[0] != 'single') {
            dataList = this.data.levelTwo[levelOneId];
        }

        dataList.map((item, index) => {

            if (levelTwoId == item.id) {
                let tmpTwo = dataList[index];

                // 取消选中
                if (tmpTwo.checked) {
                    // 删除预缓存数组
                    that.delStorageData(tmpData);

                    // checked
                    tmpTwo.checked = !tmpTwo.checked;
                // 选中
                } else {
                    // 多选 - 判断当前选项个数
                    if (that.data.currentNum >= that.data.num) {
                        app.util.showModal('当前最多可选择' + that.data.num + '项');
                        return false;
                    }

                    // checkbox
                    tmpTwo.checked = true;

                    // 存储预缓存数组
                    that.setStorageData(tmpData);

                    // 排斥父级选中状态
                    let storageData = this.data.storageData;

                    let saveArr = [];

                    for (let i in storageData) {
                        let item = storageData[i];
                        let index = i;
                        let attr = 'p' + levelOneId;

                        if (item.hasOwnProperty(attr)) {
                            // 取消checkbox
                            dataOne.map((itemOne, indexOne) => {
                                if (item[attr] == itemOne.id) {
                                    dataOne[indexOne].checked = false;
                                    this.setData({ levelOne: dataOne });
                                }
                            });
                        } else {
                            // 保留数组
                            saveArr.push(item);
                        }
                    }

                    this.setData({ storageData: saveArr });
                    this.setData({ currentNum: Object.keys(this.data.storageData).length });
                }

                that.setData({ levelTwo: that.data.levelTwo });
            }

        });
    }
})