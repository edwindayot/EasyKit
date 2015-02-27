<div id="container_event" ng-controller="event">
    <div ng-repeat="event in data track by $index"> 
        <img ng-repeat="photos in event.events_medias|limitTo:1" src="{{photos.medias_file}}" class="video_header">
    
        <div id="present_event">
            <h2>{{event.events_name}}</h2>
    
            <div class="trait"></div>
    
            <p>{{event.events_description}}</p>
    
            <div class="gallery_event">
                <img ng-repeat="photos in event.events_medias" src="{{photos.medias_file}}" alt="" title=""> 
            </div>
        </div>
    
        <div class="trait_vertical"></div>
    
        <div id="info_event">
            <ul class="detail">
                <li>
                    <a href="" class="lien_info">
                        <ul>
                            <li ng-controller="likes">
                                <img src="<?= HTML::link('default/images/like.png'); ?>" data-id="{{event.events_id}}" ng-click="like($event)" class="like" title="Like this event" width="21" alt="like">
                                <div class="spinner-like"></div>
                                <span class="like_number">{{event.events_like}}</span>
                            </li>
                        </ul>
                    </a>
                </li>
    
                <li>Location : {{event.events_address}}</li>
    
                <li>Date : {{event.events_starttime}}</li>
    
                <li>Duration : 1 day (~8 hours)</li>
    
                <li class="price">Price : 100€</li>
            </ul>
            
            <a href="" class="start">Start my pack</a>
    
            <ul class="share">
                <li>Share this event :</li>
            </ul>
        </div>
    </div>
</div>

