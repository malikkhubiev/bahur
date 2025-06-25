import requests
from bs4 import BeautifulSoup

url = "https://vk.com/@bahur_store-optovye-praisy-ot-bahur"
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

response = requests.get(url, headers=headers)
soup = BeautifulSoup(response.text, "html.parser")

# Получаем текст
text = soup.get_text(separator="\n", strip=True)

# Получаем все ссылки
links = [a['href'] for a in soup.find_all('a', href=True)]

with open("bahur_data.txt", "w", encoding="utf-8") as f:
    f.write(text + "\n\nСсылки:\n" + "\n".join(links))

print("Данные успешно сохранены в bahur_data.txt") 