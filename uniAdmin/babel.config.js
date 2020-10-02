/**
 * 打包模式
 * true:全量打包
 * false:按需打包
 * @type {boolean}
 */
let babelMode = true;

let babelConfig = {};
if (babelMode){
  babelConfig = {
    presets: [
      '@vue/cli-plugin-babel/preset'
    ]
  }
} else {
  /**
   * 按需打包 element UI组件
   * npm i @babel/preset-env -D
   * @type {{}}
   */
  babelConfig = {
    presets: [
      '@vue/cli-plugin-babel/preset',
      ["@babel/preset-env", { "modules": false }]
    ],
    "plugins": [
      [
        "component",
        {
          "libraryName": "element-ui",
          "styleLibraryName": "theme-chalk"
        }
      ]
    ]
  }
}

module.exports = babelConfig
