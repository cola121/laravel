let app = getApp()

Page({

    /**
     * 页面的初始数据
     */
    data: {
        dataMap: '',
        provinceList: [],
        cityList: [],
        countyList: [],

        provinceId: 0,
        provinceName: '',
        cityId: 0,
        cityName: '',
        countyId: 0,
        countyName: '',

        current: 0
    },

    onLoad: function (options) {
        let dataMap = app.getMetaData()
        let provinceList = []

        for (let province of dataMap.jobloc_pro) {
            provinceList.push({ id: province.id, name: province.name })
        }

        this.setData({
            dataMap: dataMap,
            provinceList: provinceList
        })
    },

    /**
     * 选择省份
     */
    chooseProvince: function (e) {
        let provinceId = e.currentTarget.dataset.code
        let provinceName = e.currentTarget.dataset.name
        let dataMap = this.data.dataMap
        let cityList = []
        
        for (let i in dataMap.jobloc_city[provinceId]) {
            let id = dataMap.jobloc_city[provinceId][i].id
            let name = dataMap.jobloc_city[provinceId][i].name
            let city = { id: id, name: name }
            cityList.push(city)
        }
        this.setData({
            cityList: cityList,
            provinceId: provinceId,
            provinceName: provinceName,
            cityId: 0,
            cityName: '',
            countyList: [],
            countyId: 0,
            countyName: ''
        })

        this.setData({
            current: 1
        })
    },

    /**
     * 选择城市
     */
    chooseCity: function (e) {
        let dataMap = this.data.dataMap
        let cityId = e.currentTarget.dataset.code
        let cityName = e.currentTarget.dataset.name
        
        let countyList = []

        for (let i in dataMap.jobloc_district[cityId]) {
            let id = dataMap.jobloc_district[cityId][i].id
            let name = dataMap.jobloc_district[cityId][i].name
            let county = { id: id, name: name }
            countyList.push(county)
        }

        this.setData({
            countyList: countyList,
            cityId: cityId,
            cityName: cityName,
            countyId: 0,
            countyName: ''
        })

        if (countyList.length > 0) this.setData({current: 2})
        if (countyList.length == 0) this.closeDialog()
    },

    /**
     * 选择区
     */
    chooseCounty: function (e) {
        let countyId = e.currentTarget.dataset.code
        let countyName = e.currentTarget.dataset.name
        this.setData({
            countyId: countyId,
            countyName: countyName
        })

        this.closeDialog()
    },

    /**
     * 切换swiper-item
     */
    swiperChange: function (e) {
        let current = e.detail.current
        this.setData({
            current: current
        })
    },

    /**
     * 打开选择窗口
     */
    openDialog: function () {
        this.setData({
            isShow: true
        })
    },

    /**
     * 关闭选择窗口
     */
    closeDialog: function () {
        this.setData({
            isShow: false
        })
    }
})