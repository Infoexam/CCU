import Extend from './extend'

function install(Vue, router) {
  Extend(Vue, router)
}

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(install)
}

export default install
