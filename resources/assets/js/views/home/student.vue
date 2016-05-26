<template>
  <div class="row" style="margin-top: 5%;">
    <div class="col s12 m4">
      <a v-link="{ name: 'practice' }">
        <div class="card hoverable">
          <div class="card-image">
            <img src="/assets/images/practice.png">
          </div>

          <div class="card-action">
            <a class="cursor-pointer">{{ $t('practice.title') }}</a>
          </div>
        </div>
      </a>
    </div>

    <div class="col s12 m4">
      <a href="#">
        <div class="card hoverable">
          <div class="card-image">
            <img src="/assets/images/apply.png">
          </div>

          <div class="card-action">
            <a class="cursor-pointer">預約（建構中）</a>
          </div>
        </div>
      </a>
    </div>

    <div class="col s12 m4">
      <a href="#">
        <div class="card hoverable">
          <div class="card-image">
            <img src="/assets/images/exam.png">
          </div>

          <div class="card-action">
            <a class="cursor-pointer">考試（建構中）</a>
          </div>
        </div>
      </a>
    </div>
  </div>
</template>

<script type="text/babel">
  export default {
  }
</script>
