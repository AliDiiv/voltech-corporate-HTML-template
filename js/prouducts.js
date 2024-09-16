
    let cats;
window.addEventListener("load", ()=>{
  cats = document.querySelectorAll("#cats div.row");
});

const switchCat = id => {
  for(const i of cats){
    if(i.id === `cat${id}`){
      i.classList.remove("d-none");
    }else{
      i.classList.add("d-none");
    }
  }
}