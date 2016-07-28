let httpQueue = 0

export default function (request, next) {
  if (0 === httpQueue++) {
    this.$progress.start()
  } else {
    this.$progress.increase(15)
  }

  next(response => {
    if (! response.ok) {
      httpQueue = 0

      this.$progress.failed()
    } else {
      --httpQueue

      if (0 === httpQueue) {
        this.$progress.finish()
      } else if (0 < httpQueue) {
        this.$progress.increase(15)
      }
    }
  })
}
