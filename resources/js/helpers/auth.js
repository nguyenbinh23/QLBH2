export function login(credentitals){
    return new Promise((res,reject) => {
        axios.post('/api/auth/login',credentitals)
            .then((response) => {
                res(response.data);
            })
            .catch((error) => {
                console.log(error)
                reject('Sai tài khoản hoặc mật khẩu');
            })
    })
}

export function getLocalUser(){
    const usesStr = localStorage.getItem("user");

    if(!usesStr){
        return null;
    }
    return JSON.parse(usesStr);
}
