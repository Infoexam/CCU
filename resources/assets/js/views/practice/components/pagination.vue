<template>
  <section class="center" style="margin: 1.5rem 0;">
    <ul class="pagination">
      <li class="waves-effect"><a @click="changePage(Math.max(current - 1, 1))"><i class="material-icons">chevron_left</i></a></li>

      <template v-for="i in total">
        <li :class="[current === i + 1 ? 'active' : 'waves-effect']">
          <a @click="changePage(i + 1)">{{ i + 1 }}</a>
        </li>
      </template>

      <li class="waves-effect"><a @click="changePage(Math.min(current + 1, total))"><i class="material-icons">chevron_right</i></a></li>
    </ul>
  </section>
</template>

<script>
  export default {
    props: {
      current: {
        type: Number,
        required: true,
        twoWay: true
      },

      total: {
        type: Number,
        required: true
      }
    },

    methods: {
      changePage (page) {
        if (this.current === page) {
          return
        }

        this.current = page

        window.scrollTo(0, window.scrollX)
      }
    }
  }
</script>
