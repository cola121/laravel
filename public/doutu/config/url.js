// 基础地址
// const baseUrl = 'http://wxapi.llq.goodjobs.lab/index.php'

// 线上地址
const baseUrl = 'https://wxapi.goodjobs.cn/index.php'
console.log(baseUrl);

module.exports = {
    // ====================系统级模块====================
    
    // 获取session id
    'getSessionIdUrl': `${baseUrl}/Common/GetSessionId`,
    // 工作职位 地址等元数据
    'commonMetaUrl': `${baseUrl}/Common/Meta`,
    // 位置坐标转换接口
    'getLocationUrl': `${baseUrl}/Common/Getlocation`,

    // ====================工作模块======================

    // 首页职位推荐
    'jobIndexUrl': `${baseUrl}/Job/Index`,
    // 职位详情
    'jobShowUrl': `${baseUrl}/Job/Jobshow`,
    // 企业详情
    'corpShowUrl': `${baseUrl}/Job/Corpshow`,
    // 职位申请
    'applyJobUrl': `${baseUrl}/Job/Addapply`,
    // 职位搜索扩展词
    'getKeywordsExtends': `${baseUrl}/Job/Searchextended`,
    // 职位搜索
    'searchJobList': `${baseUrl}/Job/Joblist`,

    // ====================消息模块======================
    
    // 用户消息记录数URL
    'getMessageCountUrl': `${baseUrl}/User/Messagecount`,
    // 用户邀约记录列表URL
    'getInviteHistoryUrl': `${baseUrl}/User/Invitehistory`,
    // 用户邀约记录操作URL
    'getInviteHistorySaveUrl': `${baseUrl}/User/Invitehistorysave`,
    // 用户邀约记录删除URL
    'getInviteHistoryDelUrl': `${baseUrl}/User/Invitehistorydel`,
    // 职位申请记录
    'getOutboxListUrl': `${baseUrl}/User/Outbox`,
    // 职位申请记录提醒查看
    'outboxNoticeUrl': `${baseUrl}/User/Outboxnotice`,
    // 职位申请记录删除
    'outboxDelUrl': `${baseUrl}/User/Outboxdel`,
    // 企业查看记录
    'corpViewHistoryUrl': `${baseUrl}/User/Corpviewhistory`,
    // 企业查看记录删除
    'corpViewHistoryDelUrl': `${baseUrl}/User/Corpviewhistorydel`,

    // ====================登录注册模块======================
    
    // 检查自动登录
    'checkLoginUrl': `${baseUrl}/User/CheckLogin`,
    // 登录URL
    'loginUrl': `${baseUrl}/User/Login`,
    // 登出URL
    'logoutUrl': `${baseUrl}/User/Logout`,
    // 注册
    'registerUrl': `${baseUrl}/User/Registernew`,
    // 注册验证码
    'registerSmsUrl': `${baseUrl}/User/UserRegistersms`,
    // 修改密码
    'modifyPasswordUrl': `${baseUrl}/User/ModifyPassword`,
    // 修改密码 验证码
    'modifyPasswordSmsUrl': `${baseUrl}/User/ModifyPasswordSms`,
    // 绑定用户
    'mobileCheckUrl': `${baseUrl}/User/Mobilecheck`,
    // 绑定用户 短信
    'mobileCheckSmsUrl': `${baseUrl}/User/Mobilechecksms`,

    // ====================我的模块======================

    // 个人中心信息
    'getUserCenterUrl': `${baseUrl}/User/Index`,
    // 简历预览
    'getCvShowUrl':`${baseUrl}/Cv/Cvshow`,
    // 刷新简历
    'getUpdateResumeUrl':`${baseUrl}/User/Update`,
    // 获取简历置顶显示
    'getCvTopUrl':`${baseUrl}/User/Cvtop`,
    // 简历置顶保存
    'getSaveCvTopUrl':`${baseUrl}/User/Savecvtop`,
    // 公开程度状态
    'getUserOpenSetUrl': `${baseUrl}/User/Openset`,
    // 屏蔽企业搜索
    'getOpenSearchUrl': `${baseUrl}/User/Opensearch`,
    // 屏蔽企业添加
    'openAddUrl': `${baseUrl}/User/Openadd`,
    // 屏蔽企业删除
    'openDelUrl': `${baseUrl}/User/Opendel`,
    // 公开程度状态更新
    'openUpdateUrl': `${baseUrl}/User/Openupdate`,

    // ====================简历模块======================

    // 简历完成度
    'getCvStatusUrl': `${baseUrl}/Cv/Index`,
    // 获取简历信息
    'getCvBaseInfoUrl': `${baseUrl}/Cv/Basic`,
    // 更改简历信息
    'updateCvBaseInfoUrl': `${baseUrl}/Cv/Basicsave`,


    //培训经历列表
    'getTrainListUrl': `${baseUrl}/Cv/Trainlist`,
    //培训经历内容
    'getTrainUrl': `${baseUrl}/Cv/Train`,
    //删除培训经历
    'getTrainDelUrl': `${baseUrl}/Cv/Traindel`,
    //培训经历修改
    'getTrainSaveUrl': `${baseUrl}/Cv/Trainsave`,

    //语言能力列表
    'getLanListUrl': `${baseUrl}/Cv/Lanlist`,
    //语言能力内容
    'getLanUrl': `${baseUrl}/Cv/Lan`,
    //语言能力修改
    'getLanSaveUrl': `${baseUrl}/Cv/Lansave`,
    //语言能力删除
    'getLanDelUrl': `${baseUrl}/Cv/Landel`,

    //证书列表
    'getCertListUrl': `${baseUrl}/Cv/Certlist`,
    //证书内容
    'getCertUrl': `${baseUrl}/Cv/Cert`,
    //证书修改
    'getCertSaveUrl': `${baseUrl}/Cv/Certsave`,
    //证书删除
    'getCertDelUrl': `${baseUrl}/Cv/Certdel`,

    //IT技能列表
    'getItListUrl': `${baseUrl}/Cv/Itlist`,
    //IT技能内容
    'getItUrl': `${baseUrl}/Cv/It`,
    //IT技能修改
    'getItSaveUrl': `${baseUrl}/Cv/Itsave`,
    //IT技能删除
    'getItDelUrl': `${baseUrl}/Cv/Itdel`,

    //自我评价内容
    'getEvalUrl': `${baseUrl}/Cv/Eval`,
    //自我评价保存
    'getEvalSaveUrl': `${baseUrl}/Cv/Evalsave`,

    //荣誉奖励列表
    'getAwardListUrl': `${baseUrl}/Cv/Awardlist`,
    //荣誉奖励内容
    'getAwardUrl': `${baseUrl}/Cv/Award`,
    //荣誉奖励修改
    'getAwardSaveUrl': `${baseUrl}/Cv/Awardsave`,
    //荣誉奖励删除
    'getAwardDelUrl': `${baseUrl}/Cv/Awarddel`,

    //校内职务 and 社会实践列表
    'getPracListUrl': `${baseUrl}/Cv/Praclist`,
    //校内职务 and 社会实践内容
    'getPracUrl': `${baseUrl}/Cv/Prac`,
    //校内职务 and 社会实践修改
    'getPracSaveUrl': `${baseUrl}/Cv/Pracsave`,
    //校内职务 and 社会实践删除
    'getPracDelUrl': `${baseUrl}/Cv/Pracdel`,

    //项目经验列表
    'getIteListUrl': `${baseUrl}/Cv/Itelist`,
    //项目经验内容
    'getIteUrl': `${baseUrl}/Cv/Ite`,
    //项目经验修改
    'getIteSaveUrl': `${baseUrl}/Cv/Itesave`,
    //项目经验删除
    'getIteDelUrl': `${baseUrl}/Cv/Itedel`,

    //简历头像保存
    'getPhotoSaveUrl': `${baseUrl}/Cv/Photosave`,

    // 工作经验列表
    'getExpListUrl': `${baseUrl}/Cv/Explist`,
    // 工作经验 - 显示
    'getExpUrl': `${baseUrl}/Cv/Exp`,
    // 工作经验 - 删除
    'delExpUrl': `${baseUrl}/Cv/Expdel`,
    // 工作经验 - 新增|修改
    'saveExpUrl': `${baseUrl}/Cv/Expsave`,

    // 教育经验 - 列表
    'getEduListUrl': `${baseUrl}/Cv/Edulist`,
    // 教育经验 - 显示
    'getEduUrl': `${baseUrl}/Cv/Edu`,
    // 教育经验 - 删除
    'delEduUrl': `${baseUrl}/Cv/Edudel`,
    // 教育经验 - 新增|修改
    'saveEduUrl': `${baseUrl}/Cv/Edusave`,
    // 学校公司扩展词
    'getAutoComplete': `${baseUrl}/Cv/Autocomplete`,

    // 求职意向 - 获取
    'getCvWillUrl': `${baseUrl}/Cv/Will`,
    // 求职意向 - 保存
    'saveCvWillUrl': `${baseUrl}/Cv/Willsave`

}
