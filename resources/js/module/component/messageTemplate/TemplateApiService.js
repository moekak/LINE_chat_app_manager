import { API_ENDPOINTS } from "../../../config/apiEndPoint.js";
import { fetchPostOperation } from "../../util/fetch.js";
import { close_loader, open_loader } from "../modalOperation.js";

// 3. TemplateApiService.js - API通信に特化
class TemplateApiService {
    /**
     * テンプレートを作成するAPIリクエストを送信
     * @param {FormData} formData - 送信するフォームデータ
     * @return {Promise<Object>} - APIレスポンス
     */
    static async createTemplate(formData, isUpdate = false) {
        try {
            const url = isUpdate ? API_ENDPOINTS.FETCH_TEMPLATE_UPDATE: API_ENDPOINTS.FETCH_TEMPLATE_CREATE
            open_loader();
            document.getElementById("js_template_modal").classList.add("hidden")
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error("テンプレート作成でエラーが発生しました");
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error(error);
            throw error;
        } finally {
            close_loader();
        }
    }

    /**
     * カテゴリを追加するAPIリクエストを送信
     * @param {Object} data - カテゴリ情報
     * @return {Promise<Object>} - APIレスポンス
     */
    static async addCategory(data) {
        try {
            open_loader();
            const response = await fetchPostOperation(data, API_ENDPOINTS.FETCH_CREATE_CATEGORY);
            return response;
        } catch (error) {
            console.error(error);
            throw error;
        } finally {
            close_loader();
        }
    }
}


export default TemplateApiService;