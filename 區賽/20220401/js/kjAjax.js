const $ = {
    async post(url,value,fun){
        let data = new URLSearchParams();
        for (x in value) {
            data.append(x,value[x])
        }
        let result = ""
        await fetch(url, {
            method:'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        }).then(res => {
            return res.text()
        }).then(text => {
            result = text
        })
        await fun(result)
    },
    async get(url,fun){
        let result = ""
        await fetch(url, {
            method:'get'
        }).then(res => {
            return res.text()
        }).then(text => {
            result = text
        })
        await fun(result)
    }
}