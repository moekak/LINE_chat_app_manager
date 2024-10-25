export const open_modal = (modal)=>{
      document.querySelector(".bg").classList.remove("hidden")
      modal.classList.remove("hidden")
}


export const close_modal = () =>{
      const bg          =  document.querySelector(".bg")
      const modals      = document.querySelectorAll(".js_modal")
      const alerts = document.querySelectorAll(".js_alert_danger")
      const loader = document.querySelector(".loader")

      bg.addEventListener("click", ()=>{
            bg.classList.add("hidden")
            loader.classList.add("hidden")
            modals.forEach((modal)=>{
                  modal.classList.add("hidden")
            })

            if(alerts){
                  alerts.forEach((alert)=>{
                        alert.style.display = "none"
                  })
            }
      })
}

export const close_modal_by_click = (modal, btn) =>{
      const bg  =  document.querySelector(".bg")
      btn.addEventListener("click", ()=>{
            bg.classList.add("hidden")
            modal.classList.add("hidden")
      })
}