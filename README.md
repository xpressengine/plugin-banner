
<p align="center"> 
  <img src="./icon.png">
 </p>

# plugin-banner
이 어플리케이션은 Xpressengine3(이하 XE3)의 플러그인 입니다.

이 플러그인은 사이트 관리자가 직접 컨텐츠를 편집할 수 있는 배너 위젯을 제공합니다. 

사이트 관리자는 배너 위젯을 하나 생성하고 그 위젯에 출력하고 싶은 다수의 배너 아이템들을 추가하고 편집할 수 있습니다. 
생성한 배너 위젯을 테마에 직접 추가하거나 위젯 박스 또는 위젯 페이지에 추가하여 화면에 출력할 수 있습니다.

[![License](http://img.shields.io/badge/license-GNU%20LGPL-brightgreen.svg)]

# Features
- 제공되는 배너 편집기를 통하여 하나의 배너 위젯에 출력할 아이템들을 추가/삭제/편집/순서변경할 수 있습니다.
- 각 아이템에는 제목, 요약, 링크 정보를 지정할 수 있고, 이미지도 업로드할 수 있습니다.
- 각 아이템은 노출여부를 지정할 수 있고, 노출 시작 및 종료 일시를 지정할 수도 있습니다.
- 배너 위젯은 다른 위젯들처럼 테마나 위젯박스에 자유롭게 배치시킬 수 있습니다.
- 화면에 출력되고 있는 배너 위젯의 편집 버튼(사이트관리자에게만 노출됨)을 통해 바로 배너 편집기를 실행시킬 수 있습니다.

# Installation
### Console
```
$ php artisan plugin:install banner
```

### Web install
- 관리자 > 플러그인 & 업데이트 > 플러그인 목록 내에 새 플러그인 설치 버튼 클릭
- `banner` 검색 후 설치하기

### Ftp upload
- 다음의 페이지에서 다운로드
    * https://store.xpressengine.io/plugins/banner
    * https://github.com/xpressengine/plugin-banner/releases
- 프로젝트의 `plugins` 디렉토리 아래 `banner` 디렉토리명으로 압축해제
- `banner` 디렉토리 이동 후 `composer dump` 명령 실행

# Usage
배너를 생성 후 위젯 페이지에 적용하거나 page에 위젯코드를 작성해서 사용합니다.

관리자 > 플러그인 & 업데이트 > 플러그인 목록 > Banner 목록 > 설정
1. `새 배너 생성` 제목 및 스킨 선택
2. 추가
3. 생성된 배너 클릭 후 `아이템 추가`
4. 배너 클릭 시 이동할 링크, 제목, 내용, 이미지 등 입력 후 저장
5. 원하는 만큼 아이템 추가
6. 추가한 배너를 위젯 페이지에서 적용해서 사용하거나 `위젯코드`를 클릭 후 나오는 소스를 페이지에 삽입해서 사용

## License
이 플러그인은 LGPL라이선스 하에 있습니다. <https://opensource.org/licenses/LGPL-2.1>
