import Vue from 'vue'
import { router, infoexam } from './routes'

router.start(infoexam, 'main')

require(`materialize-css/js/materialize.js`)
require(`../sass/app.scss`)
