<template>
  <div id="app">
    <el-container style="padding: 0;margin: 0;">
      <el-aside class="aside-div" :class="isCollapse?'aside-div-collapse':null">
        <div class="aside-top">
          <el-popover placement="bottom" trigger="hover">
            <ul class="avatar-popover">
              <li @click="showPassword">修改密码</li>
              <li @click="logout">注销登录</li>
            </ul>
            <div slot="reference" class="avatar-div" :class="isCollapse?'aside-div-collapse':null">
              <el-avatar :size="40" :src="user.avatar"></el-avatar>
              <span style="margin-top: 10px;" :style="{fontSize:isCollapse?'12px':'14px'}">{{ user.username }}</span>
            </div>
          </el-popover>
          <el-menu
              :default-active="''+currentTab.id"
              :collapse="isCollapse"
              background-color="#465161"
              text-color="#fff"
              active-text-color="#ffd04b"
              style="border: none"
          >
            <template v-for="menu in menus">
              <el-submenu :key="menu.id" :index="''+menu.id" v-if="menu.children.length > 0">
                <template slot="title">
                  <i :class="menu.icon" v-if="menu.icon"></i>
                  <span class="menu_title">{{ menu.title }}</span>
                </template>
                <template v-for="child in menu.children">
                  <el-submenu :key="child.id" :index="''+child.id" v-if="child.children.length > 0">
                    <template slot="title">
                      <i :class="child.icon" v-if="child.icon"></i>
                      <span class="menu_title">{{ child.title }}</span>
                    </template>
                    <template v-for="grandson in child.children">
                      <el-menu-item :key="grandson.id" :index="''+grandson.id" @click="openMenu(grandson)">
                        <i :class="grandson.icon" v-if="grandson.icon"></i>
                        <span class="menu_title" slot="title">{{ grandson.title }}</span>
                      </el-menu-item>
                    </template>
                  </el-submenu>
                  <el-menu-item :key="child.id" :index="''+child.id" @click="openMenu(child)" v-else>
                    <i :class="child.icon" v-if="child.icon"></i>
                    <span class="menu_title" slot="title">{{ child.title }}</span>
                  </el-menu-item>
                </template>
              </el-submenu>
              <el-menu-item :key="menu.id" :index="''+menu.id" @click="openMenu(menu)" v-else>
                <i :class="menu.icon" v-if="menu.icon"></i>
                <span class="menu_title" slot="title">{{ menu.title }}</span>
              </el-menu-item>
            </template>
          </el-menu>
        </div>
        <div class="aside-bottom">
          <div class="el-menu-item is-active collapse-icon" :class="isCollapse?'aside-div-collapse':null" @click="triggerCollapse">
            <i class="el-icon-s-unfold" v-if="isCollapse"></i>
            <i class="el-icon-s-fold" v-else></i>
          </div>
        </div>
      </el-aside>
      <el-main class="main-div">
        <el-tabs class="tabContainer" :value="currentTab.url" type="border-card"  @tab-click="changeTab" @tab-remove="removeTab">
          <template v-for="tab in tabs">
            <el-tab-pane :key="tab.url" :name="tab.url" :closable="tab.closable">
              <span slot="label" @contextmenu.prevent="showContextMenu($event,tab)"><i :class="tab.icon" v-if="tab.icon"></i> {{ tab.title }}</span>
              <div class="iframeDiv">
                <iframe class="iframe" :ref="tab.url" :src="tab.url" v-if="tab.url"></iframe>
                <div style="margin: 0;padding: 0;text-align: center;color: red;font-size: 24px;" v-else>链接无效</div>
              </div>
            </el-tab-pane>
          </template>
        </el-tabs>
      </el-main>
      </el-container>
    <context-menu class="right-menu" :offset="contextMenuOffset">
      <template v-slot:menuItem>
        <li @click="refreshTab">刷新标签</li>
        <li @click="closeTab">关闭标签</li>
        <li @click="closeOtherTab">关闭其他</li>
        <li @click="closeAllTab">关闭全部</li>
        <li @click="openTab">新窗口打开</li>
      </template>
    </context-menu>
    <vue-mask ref="mask" :z_index="999" :opacity="0"></vue-mask>
    <el-dialog
        title="修改登录密码"
        :visible.sync="dialogVisible"
        width="360px">
      <el-form ref="password" @submit.native.prevent label-width="100px">
        <el-form-item label="原登录密码">
          <el-input v-model="password.old" placeholder="请输入原登录密码" show-password></el-input>
        </el-form-item>
        <el-form-item label="新登录密码">
          <el-input v-model="password.new" placeholder="请输入新登录密码" show-password></el-input>
        </el-form-item>
        <el-form-item label="确认新密码">
          <el-input v-model="password.repeat" placeholder="请再次确认新密码" show-password></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="changePassword">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import iAjax from "@/utils/iAjax";
import Config from '@/config'
import Token from '@/utils/token'
import CheckLogin from '@/utils/check_login'
import ContextMenu from '@/components/ContextMenu.vue'
import VueMask from "@/components/VueMask.vue";
export default {
  name: "Main",
  components: {
    ContextMenu,VueMask
  },
  data(){
    return {
      user:{
        username:'未登录',
        avatar:'https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png',
      },
      password:{
        old:'',
        new:'',
        repeat:''
      },
      menus:[],
      tabs:[],
      currentTab:{},
      contextMenuTab:{},
      isCollapse:false,
      contextMenuOffset: {
        offsetLeft: 0,
        offsetWidth: 0,
        clientX: 0,
        clientY: 0
      },
      dialogVisible:false,
    }
  },
  mounted() {
    this.checkLogin();
  },
  methods: {
    //右键菜单-新窗口打开
    openTab() {
      let self = this;
      window.open(self.contextMenuTab.url,'_blank');
      self.$refs.mask.closeMask();
    },
    //右键菜单-关闭所有标签
    closeAllTab() {
      let self = this;
      self.tabs = self.tabs.filter(item => item.closable === false);
      //切换数组最后一项为当前激活标签
      self.currentTab = self.tabs.length>0?self.tabs[self.tabs.length-1]:[];
      self.$refs.mask.closeMask();
    },
    //右键菜单-关闭其他标签
    closeOtherTab() {
      let self = this;
      self.tabs = self.tabs.filter(item => item.url === self.contextMenuTab.url || item.closable === false);
      //切换数组最后一项为当前激活标签
      self.currentTab = self.tabs.length>0?self.tabs[self.tabs.length-1]:[];
      self.$refs.mask.closeMask();
    },
    //右键菜单-关闭标签
    closeTab() {
      let self = this;
      if (self.contextMenuTab.closable) {
        self.removeTab(self.contextMenuTab.url);
      } else {
        self.$message.error('该页面无法关闭');
      }
      self.$refs.mask.closeMask();
    },
    //右键菜单-刷新标签
    refreshTab() {
      let self = this;
      if (self.contextMenuTab.url){
        self.$refs[`${self.contextMenuTab.url}`][0].contentWindow.location.reload(true);
      }
      self.$refs.mask.closeMask();
    },
    //关闭标签
    removeTab(url) {
      let self = this;
      self.tabs = self.tabs.filter(item => item.url !== url);
      //切换数组最后一项为当前激活标签
      self.currentTab = self.tabs.length>0?self.tabs[self.tabs.length-1]:[];
    },
    //切换标签
    changeTab(tab) {
      let self = this;
      if (self.currentTab.url !== tab.name){
        self.currentTab = self.tabs.find(item => item.url === tab.name);
      }
    },
    //打开菜单
    openMenu(menu) {
      let self = this;
      for (let i = 0;i<self.tabs.length;i++){
        if (menu.url === self.tabs[i].url){
          self.currentTab = self.tabs[i];
          return;
        }
      }
      let tab = {...menu,closable:true};
      self.tabs.push(tab);
      self.currentTab = tab;
    },
    //注销登录
    logout() {
      let self = this;
      let data = {
        token:Token.getToken(),
      }
      iAjax.post(Config.getApi('/logout'),data).then(function (res){
        if (res.code === 0){
          self.$message.success(res.msg);
          setTimeout(function (){
            Token.removeToken(function (){
              window.location.href = Config.getPageUrl('/login.html');
            });
          },500);
        } else {
          self.$message.error(res.msg);
        }
      }).catch(function (error){
        self.$message.error(error.message);
      })
    },
    //修改密码
    changePassword() {
      let self = this;
      if (!self.password.old){
        self.$message.error('请先输入原登陆密码');
        return;
      }
      if (!self.password.new){
        self.$message.error('请输入新的登陆密码');
        return;
      }
      if (!self.password.repeat){
        self.$message.error('请再次输入新密码');
        return;
      }
      if (self.password.new !== self.password.repeat){
        self.$message.error('两次输入的密码不同');
        return;
      }
      let data = {
        token:Token.getToken(),
        old_password:self.password.old,
        new_password:self.password.new,
        repeat_password:self.password.repeat
      }
      iAjax.post(Config.getApi('/change_password'),data).then(function (res){
        if (res.code === 0){
          self.$message.success(res.msg);
          self.dialogVisible = false;
        } else {
          self.$message.error(res.msg);
        }
      }).catch(function (error){
        self.$message.error(error.message);
      })
    },
    //修改密码弹窗
    showPassword() {
      this.dialogVisible = true;
    },
    //加载页面信息-获取登录用户信息，菜单
    loadPage() {
      let self = this;
      let data = {
        token:Token.getToken(),
      }
      iAjax.post(Config.getApi('/index'),data).then(function (res){
        if (res.code === 0){
          self.user = {...self.user,...res.data.user};
          self.menus = res.data.menus;
          if (res.data.menus.length > 0){
            self.tabs.push({...res.data.menus[0],closable:false});
            self.currentTab = self.tabs[0];
          }
        } else {
          self.$message.error(res.msg);
        }
      }).catch(function (error){
        self.$message.error(error.message);
      })
    },
    //判断登录态
    checkLogin() {
      let self = this;
      CheckLogin.checkLogin().then(function (res){
        if (res.code !== 0){
          self.$message.error(res.msg);
          setTimeout(function (){
            window.location.href = Config.getPageUrl('/login.html');
          },500);
        } else {
          self.loadPage();
        }
      }).catch(function (error){
        self.$message.error(error.message);
        setTimeout(function (){
          window.location.href = Config.getPageUrl('/login.html');
        },500);
      });
    },
    //展开收缩侧边栏
    triggerCollapse() {
      this.isCollapse = !this.isCollapse;
    },
    //显示右键菜单
    showContextMenu(event,tab){
      this.contextMenuTab = tab;
      this.contextMenuOffset.offsetLeft = this.$el.getBoundingClientRect().left // container margin left
      this.contextMenuOffset.offsetWidth = this.$el.offsetWidth // container width
      this.contextMenuOffset.clientX = event.clientX
      this.contextMenuOffset.clientY = event.clientY
      this.$refs.mask.showMask();
    },
  }
}
</script>

<style scoped>
/*侧边栏*/
.aside-div {
  width: 200px !important;
  height: 100vh;
  background-color: #465161;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow-x:hidden;
}
.aside-top {
  margin-bottom: 56px;
}
.aside-div-collapse {
  width: 64px !important;
}
.aside-div::-webkit-scrollbar
{
  width: 0;
}
.menu_title {
  font-weight: lighter;
}
/*主体*/
.main-div {
  margin: 0;
  padding: 0;
  border: none;
  height: 100vh;
}
/*标签页*/
.tabContainer {
  border: none;
  height: 100vh;
  overflow-y:hidden;
}
.tabContainer >>> .el-tabs__content{
  padding: 5px;
  overflow-y: auto;
}
.iframeDiv {
  height: calc(100vh - 50px);
  margin: 0;
  padding: 0;
  width: 100%;
}
.iframe {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  border: none;
}
/*底部折叠图标*/
.aside-bottom {
  width: 200px;
  height: 56px;
  bottom: 0;
  left: 0;
  position: fixed;
}
.el-menu-item:focus,.el-menu-item:hover {
  background-color: #38414e !important;
}
.collapse-icon {
  width: 200px;
  text-align: center;
  color: #fff !important;
  background-color: #465161;
}
/*登录头像*/
.avatar-div {
  display: flex;
  width: 200px;
  color: #ffffff;
  flex-direction: column;
  align-items: center;
  margin: 10px auto;
}
.avatar-div:focus{
  outline:0;
}
.avatar-popover {
  margin: 0;
  background: #fff;
  list-style-type: none;
  padding: 5px 0;
}
.avatar-popover li {
  list-style-type: none;
  margin: 0;
  padding: 7px 16px;
  cursor: pointer;
}
.avatar-popover li:hover {
  background: #eee;
}
</style>
<style>
body,#app{
  padding: 0;
  margin: 0;
}

::-webkit-scrollbar
{
  width: 7px;
}

/*定义滚动条轨道 内阴影+圆角*/
::-webkit-scrollbar-track
{
  border-radius: 10px;
  background-color: rgba(0,0,0,0.1);
}

/*定义滑块 内阴影+圆角*/
::-webkit-scrollbar-thumb
{
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
  background-color: rgba(0,0,0,0.1);
}
</style>