import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import Register from '@/components/Register'
import CreateEditVideo from '@/components/CreateEditVideo'
import Main from '@/components/Main'
import EditPassword from '@/components/EditPassword'
import Detail from '@/components/Detail'
import EditVideo from '@/components/EditVideo'
import VideoReport from '@/components/VideoReport'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/',
      name: 'Register',
      component: Register
    },
    {
      path: '/createEditVideo',
      name: 'CreateEditVideo',
      component: CreateEditVideo
    },
    {
      path: '/main',
      name: 'Main',
      component: Main
    },
    {
      path: '/editPassword',
      name: 'EditPassword',
      component: EditPassword
    },
    {
      path: '/detail',
      name: 'Detail',
      component: Detail
    },
    {
      path: '/editVideo',
      name: 'EditVideo',
      component: EditVideo
    },
    {
      path: '/video-report',
      name: 'VideoReport',
      component: VideoReport
    }
  ]
})
