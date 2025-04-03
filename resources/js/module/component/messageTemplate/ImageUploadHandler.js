import FileUploader from "../../util/file/FileUploader.js";
import BroadcastMessageOperator from "../broadcast/BroadcastMessageOperator.js";
import DragAndDrop from "../DragAndDrop.js";
import { open_loader } from "../modalOperation.js";
import FormController from "../ui/FormController.js";
import { API_ENDPOINTS } from "./../../../config/apiEndPoint.js";
import DataValidator from "./DataValidator.js";
import InitializeInputService from "./InitializeInputService.js";

class ImageUploadHandler{
      /**
       * ファイル入力要素に変更イベントリスナーを設定する関数
       * @param {string|NodeList} selector - セレクタまたはファイル入力要素のNodeList
       * @param {HTMLElement} errorTxtElement - エラーテキスト要素
       * @param {HTMLElement} templateModalElement - テンプレートモーダル要素
       */

      #handleFileInputChange(fileInput, errorTxt, templateModal) {
            return async function(e) {

                  fileInput.dataset.crop = ""
                  fileInput.dataset.image = ""
                  InitializeInputService.initializeErrorList()
                  FormController.initializeImageCropInput();
                  
                  const file = e.target.files[0];
                  const errors = []
                  if (!file) return;

                  if(!FileUploader.isAllowedType(file.type)){
                        errors.push("許可されているファイル形式は JPG, PNGのみです")
                  }if(!FileUploader.isCorrectSize(file.size)){   
                        errors.push("画像サイズが大きすぎます。5MB以内で指定してください")     
                  }     

                  if(errors.length > 0){
                        const dataValidator = new DataValidator()
                        dataValidator.displayErrorForCreatingCategory(errors)
                        fileInput.value = ""
                        return
                  }
                  
                  // // ここにファイルアップロードやその他の処理を追加できます
                  const errorElement = document.querySelector(".js_broadcast_error");
                  const imageErrorElement = document.querySelector(".js_image_error");
                  const fileUploader = new FileUploader(file, errorTxt, errorElement, imageErrorElement, true, e.target, templateModal);
                  await fileUploader.fileOperation();

                  // 画像プレビューを設定
                  const objectURL = URL.createObjectURL(file);
                  FormController.templateImageStyle(fileInput, objectURL)
                  
                  // ドラッグ＆ドロップの初期化
                  DragAndDrop.dragAndDrop("accordion", true);
                  BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_GREETINGMESSAGE, true);
            };
      }

      setupFileInputs(selector, errorTxtElement, templateModalElement) {
            // セレクタが文字列の場合は要素を取得
            const fileInputs = typeof selector === 'string' ? document.querySelectorAll(selector) : selector;
            
            const errorTxt = errorTxtElement || document.querySelector(".js_error_txt");
            const templateModal = templateModalElement || document.getElementById("js_template_modal");
            
            fileInputs.forEach(fileInput => {
                  open_loader()
                  fileInput.addEventListener("change", this.#handleFileInputChange(fileInput, errorTxt, templateModal));
            });
      }


      
}

export default ImageUploadHandler;