const pages = {
    index:{
        entry:'src/pages/index/index.js',
        filename:'index.html', //相对于outputDir
        title:'管理后台',
        chunks: ['chunk-vendors', 'chunk-common', 'index']
    },
    login:{
        entry:'src/pages/login/login.js',
        filename:'login.html', //相对于outputDir
        title:'管理后台',
        chunks: ['chunk-vendors', 'chunk-common', 'login']
    }
}

const getVueConfig = function (pagesName) {
    return {
        pages:{
            [`${pagesName}`]:pages[`${pagesName}`]
        },
    };
}

const getAllConfig = function (){
    return {
        pages:pages,
    };
}

module.exports = {
    getVueConfig,
    getAllConfig
};