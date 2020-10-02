import Vue from 'vue'
import App from './Main.vue'

Vue.config.productionTip = false

//elementUI
import '@/../node_modules/element-ui/lib/theme-chalk/index.css';
import '@/elementui/business_blue/theme/index.css'
import {
  Container,
  Header,
  Aside,
  Main,
  Popover,
  Avatar,
  Menu,
  Submenu,
  MenuItem,
  MenuItemGroup,
  Icon,
  Tabs,
  TabPane,
  Message,
  Dialog,
    Button,
    Input,
    Form,
    FormItem
} from 'element-ui';

Vue.use(Container);
Vue.use(Header);
Vue.use(Aside);
Vue.use(Main);
Vue.use(Popover);
Vue.use(Avatar);
Vue.use(Menu);
Vue.use(Submenu);
Vue.use(MenuItem);
Vue.use(MenuItemGroup);
Vue.use(Icon);
Vue.use(Tabs);
Vue.use(TabPane);
Vue.use(Dialog);
Vue.use(Button);
Vue.use(Input);
Vue.use(Form);
Vue.use(FormItem);

Vue.prototype.$message = Message;

new Vue({
  render: h => h(App),
}).$mount('#app')
