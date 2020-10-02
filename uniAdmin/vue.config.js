const pages = require('./src/pages')
/**
 * 构建方式
 * 注意：每次打包都会删除原构建目录所有文件重新打包
 * true:全页面构建，打包全部文件
 * false:单页面构建，单独打包
 * @type {boolean}
 */
const buildMode = true;

let vueConfig = {
    outputDir:'../admin',
    assetsDir:'static',
};

if (buildMode){
    //全页面打包
    vueConfig = {...vueConfig,...pages.getAllConfig()};
} else {
    //单页面打包
    vueConfig = {...vueConfig,...pages.getVueConfig('index')};
}

module.exports = {
    ...vueConfig
}