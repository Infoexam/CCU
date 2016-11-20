<template>
  <div class="inline-block">
    <a
      @click="trigger()"
      class="waves-effect waves-light btn red"
    >
      <i v-if="null === text" class="material-icons">delete</i>
      <span>{{ text }}</span>
    </a>

    <div :id="id" class="modal">
      <div class="modal-content left-align">
        <h5>確定要執行此操作？</h5>
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
      },

      text: {
        type: String,
        default: null
      }
    },

    data () {
      return {
        id: Uuid.v4()
      }
    },

    methods: {
      trigger () {
        $(`#${this.id}`).modal().modal('open')
      }
    }
  }
</script>
