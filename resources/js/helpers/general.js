import app from '../app.js'

export function initialize(store, router){
    router.beforeEach((to,from,next) => {
        const requiresAuth =  to.matched.some(record => record.meta.requiresAuth);
        const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);
        const currentUser = store.state.currentUser


        if(requiresAuth && !currentUser){
            next('/login')
        }else if(requiresAdmin && requiresAuth && currentUser.quyenhan !== 'admin'){
            next('/notallowed   ')
        }else if(to.path == '/login' && currentUser){
            next('/')
        }else {
            next()
        }
    });
    axios.interceptors.response.use(null, (error) => {
        if(error.response.status == 401){
            store.commit('logout')
            router.push('/login')
        }
        return Promise.reject(error)
    })
}
