import { API_ENDPOINTS } from "../../../config/apiEndPoint.js"
import { fetchGetOperation } from "../../util/fetch.js"

class FilterCategory{
      constructor(activeFilterButton){
            this.activeFilterButton = activeFilterButton
            this.filterButtons = document.querySelectorAll(".category-btn")
            this.categoryId = ""

            this.#changeFilterBtnStyle()
            this.#fetchTemplateData()
      }

      
      #changeFilterBtnStyle(){
            this.filterButtons .forEach(btn => btn.classList.remove("active"))
            this.activeFilterButton.classList.add("active")
            this.categoryId  = this.activeFilterButton.dataset.category
      }

      async #fetchTemplateData(){
            console.log(this.categoryId);
            
            const data = await fetchGetOperation(`${API_ENDPOINTS.FETCH_TEMPLATE_DATA}/${this.categoryId}`)
            console.log(data);
            
      }
}
export default FilterCategory