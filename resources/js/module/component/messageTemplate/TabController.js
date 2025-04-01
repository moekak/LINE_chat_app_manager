// 4. TabController.js - タブ管理に特化
class TabController {
    constructor(tabs, tabContents) {
        this.tabs = tabs;
        this.tabContents = tabContents;
        this.setupListeners();
    }

    setupListeners() {
        this.tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                if(index === 0){
                    document.getElementById("template-edit-form").classList.add("hidden")
                }
                this.activateTab(index);
            });
        });
    }

    activateTab(index) {
        // アクティブタブの更新
        this.tabs.forEach(t => t.classList.remove('active'));
        this.tabs[index].classList.add('active');
        
        // タブコンテンツの表示/非表示
        this.tabContents.forEach(content => {
            content.style.display = 'none';
        });
        this.tabContents[index].style.display = 'block';
    }
}

export default TabController;