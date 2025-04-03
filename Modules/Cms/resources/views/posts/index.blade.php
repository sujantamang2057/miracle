 @extends('cms::layouts.master')

 @section('content_header')
     <section class="sc-breadcrumb">
         <div class="container-fluid">
             <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                 <ol class="breadcrumb mb-0">
                     <li class="breadcrumb-item">
                         <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.post') }}</li>
                 </ol>
             </nav>
         </div>
     </section>
 @endsection


 @section('content')
     <div class="category-list-page position-relative">
         <section class="sc-tour-list">
             <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                 <div class="heading d-flex align-items-end justify-content-between mb-sm-5 mb-3">
                     <div class="">
                         <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.post') }}</div>
                     </div>
                     <div class="d-flex align-items-center justify-content-end text-green">
                         <span><span class="span-big" id="total-count"></span>pieces in total</span>
                     </div>
                 </div>
                 <div class="search-input position-relative mx-auto">
                     {!! Form::open(['route' => ['cms.posts.search'], 'method' => 'post']) !!}
                     <input type="text" name="search" id="search" placeholder="Search popular posts" class="w-100">
                     <button type="submit" class="search-item position-absolute top-50 w-100 d-flex justify-content-center align-items-center">
                         <i class="bi bi-search"></i>
                     </button>
                     {!! Form::close() !!}
                 </div>
                 @php
                     $totalActivePosts = 0;
                 @endphp
                 @if ($postCategoryList && $postCategoryList->count())
                     @foreach ($postCategoryList as $key => $postCat)
                         <section class="sc-news-campaign mb-lg-5 mb-3">
                             <div class="mx-auto">
                                 <div class="news-title fw-bold">
                                     <div class="d-flex align-items-center">{{ $postCat->category_name }}
                                         <svg width="49" height="35" viewBox="0 0 49 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path
                                                 d="M44.5 0C44.5 0 53.9995 0 43.4995 0C32.9995 0 30.4995 6 23.9995 14C17.4995 22 13.9995 26.4167 10.4995 30.5C6.99954 34.5833 0.499538 35 0.499538 35H44.5V0Z"
                                                 fill="#fff7e2"></path>
                                         </svg>
                                     </div>
                                     @if (!empty($postCat->slug))
                                         <a href="{{ route('cms.posts.postList', ['slug' => $postCat->slug]) }}"
                                             class="view-more-btn-brown text-decoration-none rounded-pill me-3 ms-auto">
                                             <span>{{ __('cms::general.view_more') }}</span>
                                         </a>
                                     @else
                                         <a href="{{ route('cms.posts.index') }}"
                                             class="view-more-btn-brown text-decoration-none rounded-pill me-3 ms-auto">
                                             <span>{{ __('cms::general.view_more') }}</span>
                                         </a>
                                     @endif

                                 </div>
                                 <div class="articles-group">
                                     @php
                                         $postHtml = '';
                                     @endphp

                                     <div class="row row-cols-2 row-cols-xl-2 align-items-start">
                                         @php
                                             $postList = $postCat->activePosts(POST_LIST_LIMIT)->get();
                                             $totalActivePosts += $postList?->count();
                                         @endphp
                                         @if ($postList?->count())
                                             @foreach ($postList as $index => $post)
                                                 @php
                                                     $postsImg = $post->feature_image ?? '';
                                                     $postImgPath = storage_path(POST_FILE_PATH . DS . IMAGE_WIDTH_800 . DS . $postsImg);
                                                     $postImgURL =
                                                         STORAGE_DIR_NAME . '/' . POST_FILE_DIR_NAME . '/' . IMAGE_WIDTH_800 . '/' . $postsImg;
                                                     $postImg = $postsImg && file_exists($postImgPath) ? $postImgURL : DEFAULT_IMAGE_SIZE_600;
                                                 @endphp
                                                 @if ($index < 2)
                                                     <div class="d-grid hover-effect mb-sm-5 mb-3">
                                                         <a href="{{ route('cms.posts.detail', ['slug' => $post->slug]) }}" class="hover-effect">
                                                             <figure class="mb-2">
                                                                 @if ($postImg)
                                                                     <img src="{{ asset($postImg) }}" alt="{{ $post->post_title }}" />
                                                                 @else
                                                                     <img src="{{ asset('img/no-image/600x900.png') }}" alt="{{ $post->post_title }}" />
                                                                 @endif
                                                             </figure>
                                                         </a>
                                                         <div class="latest-info d-flex flex-column flex-fill">
                                                             <div class="date fw-semibold mb-1">
                                                                 {{ dateFormatter($post->published_date, DATE_FORMAT_NO_ZERO) }}
                                                             </div>
                                                             <a class="text-decoration-none"
                                                                 href="{{ route('cms.posts.detail', ['slug' => $post->slug]) }}">
                                                                 <div class="fw-bold fs-4 color-theme mb-2">
                                                                     {{ $post->post_title }}
                                                                 </div>
                                                             </a>
                                                             <p class="para-text">
                                                                 {!! trimText($post->description, POST_TRIM_LEN, 'all') !!}
                                                             </p>
                                                         </div>
                                                     </div>
                                                 @else
                                                     @php
                                                         $postHtml .= '<div class="d-grid hover-effect mb-sm-5 mb-3">';
                                                         $postHtml .=
                                                             ' <a href="' .
                                                             route('cms.posts.detail', ['slug' => $post->slug]) .
                                                             '"
                                                            class="hover-effect">';
                                                         $postHtml .= ' <figure class="mb-2">';
                                                         if ($postImg && file_exists($postImgPath)) {
                                                             $postHtml .= '<img src="' . asset($postImgURL) . '" alt="' . $post->post_title . '" />';
                                                         } else {
                                                             $postHtml .=
                                                                 '<img src="' . DEFAULT_IMAGE_SIZE_600 . '" alt="' . $post->post_title . '" />';
                                                         }
                                                         $postHtml .= '</figure>
                                                              </a>';
                                                         $postHtml .= '<div class="latest-info d-flex flex-column flex-fill">';
                                                         $postHtml .=
                                                             '<div class="fs-6 fw-semibold mb-2">' .
                                                             dateFormatter($post->published_date, DATE_FORMAT_NO_ZERO) .
                                                             '</div>';
                                                         $postHtml .=
                                                             '<a class="text-decoration-none" href="' .
                                                             route('cms.posts.detail', ['slug' => $post->slug]) .
                                                             '">';
                                                         $postHtml .=
                                                             '<div class="date fw-semibold color-theme mb-1">' . $post->post_title . '</div>';
                                                         $postHtml .=
                                                             '</a>
                                            <p class="para-text">' .
                                                             trimText($post->description, POST_TRIM_LEN, 'all') .
                                                             '</p>
                                            </div>
                                        </div>';
                                                     @endphp
                                                 @endif
                                             @endforeach
                                         @else
                                             <div class="not-found-class">
                                                 <div class="fs-1 fw-bold color-theme text-center">
                                                     {{ __('common::messages.data_not_available') }}
                                                 </div>
                                                 <div class="fs-5 text-center">
                                                     <a href="{{ route('cms.home.index') }}" class="btn btn-success rounded">
                                                         <span>{{ __('cms::general.home') }}</span>
                                                     </a>
                                                 </div>
                                             </div>
                                         @endif
                                     </div>
                                     @if ($postHtml)
                                         <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 align-items-start">
                                             {!! $postHtml !!}
                                         </div>
                                     @endif
                                 </div>
                             </div>
                         </section>
                     @endforeach
                     <nav aria-label="Pagination" class="pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                         {{ $postCategoryList->links() }}
                     </nav>
                 @else
                     <div class="not-found-class">
                         <div class="fs-1 fw-bold color-theme text-center">
                             {{ __('common::messages.data_not_available') }}
                         </div>
                         <div class="fs-5 text-center">
                             <a href="{{ route('cms.home.index') }}" class="btn btn-success bg-color-green rounded-full">
                                 <span>{{ __('cms::general.home') }}</span>
                             </a>
                         </div>
                     </div>
                 @endif

             </div>
         </section>
     </div>
     @push('page_scripts')
         <script>
             $(document).ready(function() {
                 document.getElementById('total-count').textContent = {{ $totalActivePosts }}
             });
         </script>
     @endpush
 @endsection
