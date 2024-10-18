export const resizeImage = (file, maxWidth = 160) => {
      return new Promise((resolve, reject) => {
            const reader = new FileReader()
            reader.onload = (e) => {
                  const img = new Image();
                  img.src = e.target.result;
      
                  img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        const scaleSize = maxWidth / img.width;
                        canvas.width = maxWidth;
                        canvas.height = img.height * scaleSize;
            
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            
                        // 圧縮を省略し、元の形式とクオリティを維持
                        const resizedImage = canvas.toDataURL(file.type);
                        resolve(resizedImage);
                  };
      
                  img.onerror = () => reject(new Error('画像の読み込みに失敗しました。'));
            };
            reader.onerror = () => reject(new Error('ファイルの読み込みに失敗しました。'));
            reader.readAsDataURL(file);
      });
};

export const fileOperation = async () => {
      const fileInput = document.getElementById('fileInput'); // 適切なIDを使用してください
      const file = fileInput.files[0];

      try {
            const resizedImage = await resizeImage(file);
            // ここで resizedImage を使用して後続の処理を行う
            // 例: socket.emit('image', { sender_id, url: resizedImage, sender_type });
      } catch (error) {
            alert(error.message);
      }
};