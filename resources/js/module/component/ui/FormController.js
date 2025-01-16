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
}

export default FormController;