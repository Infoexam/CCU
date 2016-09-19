<template>
  <div class="inline-block">
    <a
      @click="trigger()"
      class="waves-effect waves-light btn red"
    ><i class="material-icons">delete</i></a>

    <div :id="id" class="modal">
      <div class="modal-content left-align">
        <h5>確定要刪除該資料？</h5>
        <p>此操作無法復原，請再次確認。</p>
      </div>
      <div class="modal-footer">
        <a
          @click="$parent.destroy(target)"
          class="modal-action modal-close waves-effect btn-flat red darken-1"
        >確定</a>
        <a class="modal-action modal-close waves-effect btn-flat">取消</a>
      </div>
    </div>
  </div>
</template>

<script>
  import Uuid from 'node-uuid'

  export default {
    props: {
      target: {
        required: true
      }
    },

    data () {
      return {
        id: Uuid.v4()
      }
    },

    methods: {
      trigger () {
        $(`#${this.id}`).openModal()
      }
    }
  }
</script>
