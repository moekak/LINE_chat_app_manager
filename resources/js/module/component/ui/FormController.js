import { close_loader } from "../modalOperation.js"


class FormController{
    static initializeInput(){
        const inputField = document.querySelector(".js_message_input")
        inputField.value = ""
    }

    static initializeImageCropInput(){
        const inputFiled = document.getElementById("js_url_input")
        const button = document.getElementById("js_change_area")
        const submitButton = document.querySelector(".preview_submit_btn ")
        const choices = document.getElementsByName('choice')

        document.querySelector(".js_image_error").classList.add("hidden")

        choices.forEach((choice)=>{
            if(choice.value === "off"){
                choice.checked = true
            }else{
                choice.checked = false
            }
        })

        const url_wrapper = document.getElementById("js_url_setting");
        url_wrapper.classList.add("hidden")

        button.classList.add("disabled_btn")
        if(button.innerHTML === "選択範囲変更"){

            button.style.backgroundColor = "#fff"
            button.innerHTML = "選択範囲確定"
        }

        submitButton.classList.remove("disabled_btn")
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


    static populateSelectOptions(id, category_name){
        const option = document.createElement("option")
        const selectParentElement = document.getElementById("category-select")
        option.value = id
        option.innerHTML = category_name
        selectParentElement.appendChild(option)
    }


    static templateImageStyle(fileInput, objectURL){
        const imageElement = fileInput.parentElement.querySelector(".image_element");
        const placeholderText = fileInput.parentElement.querySelector(".image-placeholder-txt");
        
        // 画像プレビューを設定
        imageElement.src = objectURL;
        imageElement.classList.add("active");
        placeholderText.classList.add("hidden");
    }

    static showCropperSetting(){
        const setting = document.getElementById("js_url_setting")
        setting.classList.remove("hidden")
        document.getElementById("js_preview_submit_btn").classList.add("disabled_btn")

        const checkOff = document.getElementById("flexRadioDefault1")
        const checkOn = document.getElementById("flexRadioDefault2")

        checkOff.checked = false
        checkOn.checked = true
        close_loader()

    }
}

export default FormController;