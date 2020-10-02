<template>
  <div id="app">
    <el-row type="flex" justify="center">
      <el-col style="display: flex;justify-content: center">
        <el-card class="box-card" style="width: 520px;margin-top: 210px;">
          <div slot="header" style="text-align: center;">
            <span style="font-size: 21px;font-weight: lighter;">登录后台</span>
          </div>
          <el-form ref="login" @submit.native.prevent label-width="100px">
            <el-form-item label="用户名">
              <el-input v-model="username" placeholder="请输入用户名"></el-input>
            </el-form-item>
            <el-form-item label="登录密码">
              <el-input v-model="password" placeholder="请输入登录密码" show-password></el-input>
            </el-form-item>
            <el-form-item style="text-align: right;">
              <el-button type="primary" @click="login">登录</el-button>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Config from '@/config'
import Token from '@/utils/token'
import Login from '@/utils/login'

export default {
  name: "Login",
  data() {
    return {
      username:'',
      password:'',
    }
  },
  mounted() {
    Token.writeTokenPrefix();
  },
  methods:{
    login() {
      let self = this;
      if (!self.username){
        self.$message.error('请输入用户名');
        return;
      }
      if (!self.password){
        self.$message.error('请输入密码');
        return;
      }
      let data = {
        username:self.username,
        password:self.password
      }
      Login.login(data).then(function (res){
        if (res.code === 0){
          //登录成功
          self.$message.success('登录成功');
          setTimeout(function (){
            window.location.href = Config.getPageUrl('/index.html');
          },1000);
        } else {
          self.$message.error(res.msg)
        }
      }).catch(function (error){
        self.$message.error(error.message)
      })
    }
  }
}
</script>

<style>
body,#app{
  padding: 0;
  margin: 0;
}
</style>

<style scoped>

</style>