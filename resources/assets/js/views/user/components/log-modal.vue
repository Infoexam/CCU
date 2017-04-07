<template>
  <div id="test-result" class="modal modal-fixed-footer" style="width: 80%;">
    <div class="modal-content">
      <pre v-if="'string' === typeof log" v-html="log"></pre>
      <template v-else>
        <table class="bordered striped centered">
          <thead>
            <tr>
              <th>代號</th>
              <th style="width: 80%;">題目</th>
              <th>作答結果</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="question in log.questions">
              <td>{{ $key }}</td>
              <td style="text-align: left;">{{ question }}</td>
              <td>
                <available-icon :available="log.result.includes($key)"></available-icon>
              </td>
            </tr>
          </tbody>
        </table>
      </template>
    </div>
    <div class="modal-footer">
      <a class="modal-action modal-close waves-effect btn-flat">關閉</a>
    </div>
  </div>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'

  export default {
    props: {
      log: {
        type: [String, Object],
        required: true,
        default: ''
      }
    },

    components: {
      AvailableIcon
    }
  }
</script>
