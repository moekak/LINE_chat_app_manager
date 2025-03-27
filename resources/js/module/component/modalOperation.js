
import FormController from "./ui/FormController.js"

export const open_modal = (modal)=>{
      document.querySelector(".bg").classList.remove("hidden")
      modal.classList.remove("hidden")
}


export const close_modal = () =>{
      const bg  =  document.querySelector(".bg")
      const modals = document.querySelectorAll(".js_modal")
      const alerts = document.querySelectorAll(".js_alert_danger")
      const loader = document.querySelector(".loader")
      const imageEditModal = document.getElementById("js_image_edit_modal")

      if(document.getElementById("js_cancel_btn")){
            document.getElementById("js_cancel_btn").addEventListener("click", ()=>{
                  document.getElementById("js_broadcast_confirm_modal").classList.add("hidden")
                  document.querySelector(".broadcasting_message_modal").style.zIndex = "999"
            })  
      }
      

      bg.addEventListener("click", ()=>{

            if(imageEditModal.classList.contains("hidden") == false){
                  imageEditModal.classList.add("hidden")
                  FormController.initializeFileUpload()

                  return
            }

            if(document.querySelector(".broadcasting_message_modal").classList.contains("hidden") == false){
                  document.getElementById("js_broadcast_confirm_modal").classList.remove("hidden")
                  document.querySelector(".broadcasting_message_modal").style.zIndex = "997"
                  return

            }

            // 通常処理
            bg.classList.add("hidden")
            loader.classList.add("hidden")
            modals.forEach((modal)=>{
                  modal.classList.add("hidden")
            })

            if(alerts){
                  alerts.forEach((alert)=>{
                        alert.classList.add("hidden")
                  })
            } 


      })
}

export const open_loader =() =>{
      const loader = document.querySelector(".loader")
      const bg  =  document.querySelector(".bg")

      bg.classList.remove("hidden")
      loader.classList.remove("hidden")
}
export const close_loader =() =>{
      const loader = document.querySelector(".loader")
      loader.classList.add("hidden")
}

export const hide_bg = ()  =>{
      const bg  =  document.querySelector(".bg")
      bg.classList.add("hidden")
}


export const close_modal_by_click = (modal, btn) =>{
      const bg  =  document.querySelector(".bg")
      btn.addEventListener("click", ()=>{
            bg.classList.add("hidden")
            modal.classList.add("hidden")
      })
}

export const open_loader_template =() =>{
      const loader = document.querySelector(".loader")
      const bg = document.querySelector(".bg_temaplteModal")
      const modal = document.getElementById("js_template_modal")

      modal.style.zIndex = 998
      loader.style.zIndex = 999
      loader.classList.remove("hidden")
      bg.classList.remove("hidden")
}
export const close_loader_template =() =>{
      const loader = document.querySelector(".loader")
      const bg = document.querySelector(".bg_temaplteModal")
      const modal = document.getElementById("js_template_modal")

      modal.style.zIndex = 999
      loader.style.zIndex = 998
      loader.classList.add("hidden")
      bg.classList.add("hidden")
}