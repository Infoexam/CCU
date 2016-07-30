<template>
  <div class="row middle-xs">
    <div v-for="item in navigation" class="col-xs-12 col-sm-4">
      <a v-link="item.link">
        <div class="card hoverable">
          <div class="card-image">
            <img :src="`/assets/images/${item.title}.png`":alt="item.title">
          </div>

          <div class="card-action">
            <a v-link="item.link">{{ $t(item.title + '.title') }}</a>
          </div>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        navigation: [
          { link: { name: 'practice' }, title: 'practice' },
          { link: '#', title: 'apply' },
          { link: '#', title: 'exam' }
        ]
      }
    }
  }
</script>
