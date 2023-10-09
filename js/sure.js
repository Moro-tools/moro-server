function sure(){
    let a = confirm("are you sure ?");
    if (a){
        const form = document.getElementById("delete");
        const btn = document.getElementById("delete_btn");
        btn.setAttribute("name", "delete_all");
        form.onsubmit();
    }
}