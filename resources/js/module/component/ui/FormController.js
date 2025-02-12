import CropperOverlay from "../../util/cropper/CropperOverlay.js"

class FormController{
    static initializeInput(){
        const inputField = document.querySelector(".js_message_input")
        inputField.value = ""
    }

    static initializeImageCropInput(){
        const inputFiled = document.getElementById("js_url_input")
        const buttton = document.getElementById("js_change_area")
        const submitButton = document.querySelector(".preview_submit_btn ")
        const choices = document.getElementsByName('choice')

        document.querySelector(".js_image_error").classList.add("hidden")

        choices.forEach((choice)=>{
            if(choice.value === "off"){
                choice.checked = false
            }else{
                choice.checked = true
            }
        })

        const url_wrapper = document.getElementById("js_url_setting");
        url_wrapper.classList.remove("hidden")

        buttton.classList.add("disabled_btn")
        if(buttton.innerHTML === "選択範囲変更"){

            buttton.style.backgroundColor = "#fff"
            buttton.innerHTML = "選択範囲確定"
        }

        submitButton.classList.add("disabled_btn")
        inputFiled.value = ""

    }

    static initializeFileUpload(){
        document.querySelector(".js_upload").value = ""
    }

    static initializePreviewList(){
        document.querySelector(".js_accordion_wrapper").innerHTML = ""
    }

    static setupTextToggle(){
        const radioBtns = document.querySelectorAll(".js_display_radio")
        const textInput = document.querySelector(".js_create_text")
        const textElement = document.querySelector(".js_line_text_input")


        radioBtns.forEach((radioBtn)=>{
            radioBtn.addEventListener("change", (e)=>{
                textInput.classList.toggle("hidden", e.target.value === "0")
                if(e.target.value === "0"){
                    textElement.value = ""
                }
            })
            
        })
    }

}

export default FormController;