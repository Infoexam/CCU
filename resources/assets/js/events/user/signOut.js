// this keyword is already Vue instance

export default function() {
  this.$root.$data.user = null

  Materialize.toast('登出成功', 4000)
}
