var app = getApp()

Page({

    /**
     * 页面的初始数据
     */
    data: {
        dataMap: '',
        provinceList: {},
        provinceId: 0,
        provinceName: '',
        cityList: {},
        cityId: 0,
        cityName: '',
        countyList: {},
        countyId: 0,
        countyName: '',
        value: [0, 0, 0],
        values: [0, 0, 0],
        condition: false
    },

    onShow: function() {
    },

    onLoad: function () { 
        var dataMap = app.getMetaData()

        var provinceList = {}
        var defaultProvinceId = this.data.provinceId
        var defaultProvinceName = ''
        var cityList = {}
        var defaultCityId = this.data.cityId
        var defaultCityName = ''
        var countyList = {}
        var defaultCountyId = this.data.countyId
        var defaultCountyName = ''

        // 处理省份数据
        for (let i = 0; i < dataMap.jobloc_pro.length; i++) {
            let province = {}
            province.id = dataMap.jobloc_pro[i].id
            province.name = dataMap.jobloc_pro[i].name
            provinceList[province.id] = province.name
            //provinceList.push(province)
        }

        var provinceIdList = Object.keys(provinceList)

        if (!provinceList[defaultProvinceId]) {
            defaultProvinceId = provinceIdList[0]
        }

        defaultProvinceName = provinceList[defaultProvinceId]

        // 处理城市数据
        for (let i = 0; i < dataMap.jobloc_city[defaultProvinceId].length; i++) {
            let id = dataMap.jobloc_city[defaultProvinceId][i].id
            let name = dataMap.jobloc_city[defaultProvinceId][i].name
            cityList[id] = name
        }

        var cityIdList = Object.keys(cityList)

        if (!cityList[defaultCityId]) {
            defaultCityId = cityIdList[0]
        }

        defaultCityName = cityList[defaultCityId]

        // 处理区域数据
        if (dataMap.jobloc_district[defaultCityId]) {
            for (let i = 0; i < dataMap.jobloc_district[defaultCityId].length; i++) {
                let id = dataMap.jobloc_district[defaultCityId][i].id
                let name = dataMap.jobloc_district[defaultCityId][i].name
                countyList[id] = name
            }

            var countyIdList = Object.keys(countyList)
            
            if (!countyList[defaultCountyId]) {
                defaultCountyId = countyIdList[0]
            } 

            defaultCountyName = countyList[defaultCountyId]
        }
        
        this.setData({
            dataMap: dataMap,
            provinceList: provinceList,
            provinceId: defaultProvinceId,
            provinceName: defaultProvinceName,
            cityList: cityList,
            cityId: defaultCityId,
            cityName: defaultCityName,
            countyList: countyList,
            countyId: defaultCountyId,
            countyName: defaultCountyName
        })
    },
    /**
     * 开启选择
     */
    open: function () {
        var provinceId = this.data.provinceId
        var cityId = this.data.cityId
        var countyId = this.data.countyId

        var provinceList = this.data.provinceList
        var provinceIdList = Object.keys(provinceList)
        var cityList = this.data.cityList
        var cityIdList = Object.keys(cityList)
        var countyList = this.data.countyList
        var countyIdList = Object.keys(countyList)
        
        var value = this.data.value

        for (var i=0; i< provinceIdList.length; i++) {
            if (provinceIdList[i] == provinceId) {
                value[0] = i
                break
            }
        }

        for (var i=0; i<cityIdList.length; i++) {
            if (cityIdList[i] == cityId) {
                value[1] = i
                break
            }
        }

        for (var i=0; i<countyIdList.length; i++) {
            if (countyIdList[i] == countyId) {
                value[2] = i
                break
            }
        }

        this.setData({
            condition: !this.data.condition,
            value: value
        })
    },

    /**
     * 取消
     */
    cancel: function() {
        this.setData({
            condition: !this.data.condition
        })
    },

    /**
     * 确定
     */
    confirm: function() {
        var tmpProvinceId = this.data.tmpProvinceId || this.data.provinceId
        var tmpProvinceName = this.data.tmpProvinceName || this.data.provinceName
        var tmpCityId = this.data.tmpCityId || this.data.cityId
        var tmpCityName = this.data.tmpCityName || this.data.cityName
        var tmpCountyId = this.data.tmpCountyId || this.data.countyId
        var tmpCountyName = this.data.tmpCountyName || this.data.countyName

        this.setData({
            provinceId: tmpProvinceId,
            provinceName: tmpProvinceName,
            cityId: tmpCityId,
            cityName: tmpCityName,
            countyId: tmpCountyId,
            countyName: tmpCountyName,
            condition: !this.data.condition
        })  
        
        app.util.showModal(tmpProvinceId + ':' + tmpProvinceName + ',' + tmpCityId + ':' + tmpCityName + ',' + tmpCountyId + ':' + tmpCountyName)
    },

    /**
     * 触摸选择
     */
    bindChange: function (e) {
        var provinceList = this.data.provinceList
        var provinceIdList = Object.keys(provinceList)
        var dataMap = this.data.dataMap
        var val = e.detail.value
        var t = this.data.values;

        // 切换省份
        if (val[0] != t[0]) {
            var cityList = {}
            var cityId = 0
            var cityName = ''
            var countyList = {}
            var countyId = 0
            var countyName = ''
            var provinceId = provinceIdList[val[0]]
            var provinceName = provinceList[provinceId]
            
            // 处理城市数据
            for (let i = 0; i < dataMap.jobloc_city[provinceId].length; i++) {
                let id = dataMap.jobloc_city[provinceId][i].id
                let name = dataMap.jobloc_city[provinceId][i].name
                cityList[id] = name
            }

            var cityIdList = Object.keys(cityList)
            cityId = cityIdList[0]
            cityName = cityList[cityId]

            // 处理区域数据
            if (dataMap.jobloc_district[cityId]) {
                for (let i = 0; i < dataMap.jobloc_district[cityId].length; i++) {
                    let id = dataMap.jobloc_district[cityId][i].id
                    let name = dataMap.jobloc_district[cityId][i].name
                    countyList[id] = name
                }

                var countyIdList = Object.keys(countyList)
                countyId = countyIdList[0]
                countyName = countyList[countyId]
            }

            this.setData({
                tmpProvinceId: provinceId,
                tmpProvinceName: provinceName,
                cityList: cityList,
                tmpCityId: cityId,
                tmpCityName: cityName,
                countyList: countyList,
                countyId: 0,
                countyName: '',
                tmpCountyId: countyId,
                tmpCountyName: countyName,
                values: val,
                value: [val[0], 0, 0]
            })

            return
        }
        
        // 切换城市
        if (val[1] != t[1]) {
            var cityList = this.data.cityList
            var cityIdList = Object.keys(cityList)
            var cityId = cityIdList[val[1]]
            var cityName = cityList[cityId]
            var countyList = {}
            var countyId = 0
            var countyName = ''
           

            // 处理区域数据
            if (dataMap.jobloc_district[cityId]) {
                for (let i = 0; i < dataMap.jobloc_district[cityId].length; i++) {
                    let id = dataMap.jobloc_district[cityId][i].id
                    let name = dataMap.jobloc_district[cityId][i].name
                    countyList[id] = name
                }
                var countyIdList = Object.keys(countyList)
                countyId = countyIdList[0]
                countyName = countyList[countyId]
            }

            this.setData({
                tmpCityId: cityId,
                tmpCityName: cityName,
                countyList: countyList,
                countyId: 0,
                countyName: '',
                tmpCountyId: countyId,
                tmpCountyName: countyName,
                values: val,
                value: [val[0], val[1], 0]
            })

            return
        }

        // 切换区域
        if (val[2] != t[2]) {
            var countyList = this.data.countyList
            var countyIdList = Object.keys(countyList)
            var countyId = countyIdList[val[2]]
            var countyName = countyList[countyId]

            this.setData({
                tmpCountyId: countyId,
                tmpCountyName: countyName,
                values: val
            })
            return
        }
    }
})
