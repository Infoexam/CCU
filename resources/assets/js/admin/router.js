router.map({
    '/': {
        component: routerComponents.master,
        subRoutes: {
            '/': {
                name: 'home',
                component: routerComponents.home
            },

            '/users': {
                name: 'users.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.user.index
                    },
                    '/:user/edit': {
                        name: 'users.edit',
                        component: routerComponents.user.edit
                    }
                }
            },

            '/exam/configs': {
                name: 'exam.configs.index',
                component: routerComponents.exam.configs.index
            },

            '/exam/sets': {
                name: 'exam.sets.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.exam.sets.index
                    },
                    '/create': {
                        name: 'exam.sets.create',
                        component: routerComponents.exam.sets.create
                    },
                    '/:id/edit': {
                        name: 'exam.sets.edit',
                        component: routerComponents.exam.sets.edit
                    },
                    '/:id/questions': {
                        name: 'exam.sets.questions.index',
                        component: routerParentInstance,
                        subRoutes: {
                            '/': {
                                component: routerComponents.exam.sets.questions.index
                            },
                            'create': {
                                name: 'exam.sets.questions.create',
                                component: routerComponents.exam.sets.questions.create
                            },
                            '/:question': {
                                name: 'exam.sets.questions.show',
                                component: routerComponents.exam.sets.questions.show
                            },
                            '/:question/edit': {
                                name: 'exam.sets.questions.edit',
                                component: routerComponents.exam.sets.questions.edit
                            }
                        }
                    }
                }
            },

            '/exam/papers': {
                name: 'exam.papers.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.exam.papers.index
                    },
                    'create': {
                        name: 'exam.papers.create' ,
                        component: routerComponents.exam.papers.create
                    },
                    '/:id': {
                        name: 'exam.papers.show',
                        component: routerComponents.exam.papers.show
                    },
                    '/:id/edit' : {
                        name:'exam.papers.edit',
                        component:routerComponents.exam.papers.edit
                    }
                }
            },

            '/exam/lists': {
                name: 'exam.lists.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.exam.lists.index
                    },
                    'create': {
                        name: 'exam.lists.create' ,
                        component: routerComponents.exam.lists.create
                    },
                    '/:code': {
                        name: 'exam.lists.show',
                        component: routerComponents.exam.lists.show
                    },
                    '/:code/edit' : {
                        name:'exam.lists.edit',
                        component: routerComponents.exam.lists.edit
                    }
                }
            },

            '/announcements': {
                name: 'announcements.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.announcements.index
                    },
                    '/create': {
                        name: 'announcements.create',
                        component: routerComponents.announcements.create
                    },
                    '/:heading': {
                        name: 'announcements.show',
                        component: routerComponents.announcements.show
                    },
                    '/:heading/edit': {
                        name: 'announcements.edit',
                        component: routerComponents.announcements.edit
                    }
                }
            },

            '/website-maintenance': {
                name: 'website-maintenance.index',
                component: routerComponents.websiteMaintenance.index
            },

            '/ip-rules': {
                name: 'ip-rules.index',
                component: routerComponents.ipRules.index
            },

            '/not-found': {
                name: 'not-found',
                component: routerComponents.notFound
            }
        }
    }
});

router.redirect({
    '*': '/not-found'
});

router.start(Vue.extend(), '#infoexam');
