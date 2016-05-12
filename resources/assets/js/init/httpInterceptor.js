let httpQueue = 0

const request = function (request) {
  if (0 === httpQueue) {
    this.$progress.start()
  } else {
    this.$progress.increase(10)
  }

  ++httpQueue

  return request
}

const response = function (response) {
  if (response.ok) {
    if (1 === httpQueue) {
      this.$progress.finish()
    } else if (1 < httpQueue) {
      this.$progress.increase(10)
    }
  } else {
    this.$progress.failed()

    httpQueue = 1
  }

  --httpQueue

  return response
}

export default { request, response }
