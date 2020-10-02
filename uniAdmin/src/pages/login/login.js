import Vue from 'vue'
import Login from './Login.vue'

import '@/../node_modules/element-ui/lib/theme-chalk/index.css';
import '@/elementui/business_blue/theme/index.css'
import {
    Button, Card, Col, Container, Form, FormItem, Input,
    Message, Row
} from 'element-ui';
Vue.use(Container);
Vue.use(Button);
Vue.use(Input);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Row);
Vue.use(Col);
Vue.use(Card);
Vue.prototype.$message = Message;

Vue.config.productionTip = false

new Vue({
    render: h => h(Login),
}).$mount('#app')
