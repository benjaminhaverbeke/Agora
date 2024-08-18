export async function fetchDuration(id){

    const r = await fetch(`/salon/get-duration/${id}`, {
        method: 'GET',
        headers: {
            "Accept": "application/json",
            "Content-type": "application/json"
        }
    })
    if(r.ok === true){
        return r.json();

    }else {
        throw new Error('Impossible de contacter le serveur')
    }

}