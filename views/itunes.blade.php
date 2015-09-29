@inject('categorytags', 'Lasallecast\Lasallecastitunes\Categories')

{{-- Validate feeds at http://feedvalidator.org/ --}}

{{-- HTML special chars are a problem. One unencoded char can invalidate the feed.  --}}

<?xml version="1.0" encoding="UTF-8"?>

{{-- RSS tag with namespaces --}}
<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:creativeCommons="http://backend.userland.com/creativeCommonsRssModule"  version="2.0">

    {{-- Wrap all the tags with <channel></channel> --}}
    <channel>

        {{-- Tags for the show --}}



        <title>{!! htmlspecialchars($show->title) !!}</title>

        <atom:link href="{!! $show->itunes_show_feed_url !!}" rel="self" type="application/rss+xml"/>

        <link>{!! $show->itunes_show_url !!}</link>

        <description>
        {!! htmlspecialchars($show->itunes_show_subtitle) !!}
        </description>

        <lastBuildDate>{!! $lastBuildDate !!}</lastBuildDate>

        <language>en-US</language>

        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <generator>{!! $feedGenerator !!}</generator>

        <itunes:summary>
            {!! htmlspecialchars($show->itunes_show_summary) !!}
        </itunes:summary>

        <itunes:author>{!! $show->itunes_show_author_name !!}</itunes:author>

        <itunes:explicit>{!! $show->itunes_explicit_title !!}</itunes:explicit>

        <itunes:image href="{!! $show->itunes_show_artwork_url !!}"/>

        <itunes:owner>
            <itunes:name>{!! $show->itunes_show_author_name !!}</itunes:name>
            <itunes:email>{!! $show->itunes_show_email !!}</itunes:email>
        </itunes:owner>

        <managingEditor>{!! $show->itunes_show_email !!} ({!! $show->itunes_show_author_name !!})</managingEditor>

        @if ( Config::get('lasallecastitunes.use_creative_commons_nc_nd_40_license') == 'yes' )
        <copyright>
            This work is licensed under a Creative Commons License - Attribution-NonCommercial-NoDerivatives 4.0 International - http://creativecommons.org/licenses/by-nc-nd/4.0/
        </copyright>
        <creativeCommons:license>http://creativecommons.org/licenses/by-nc-nd/4.0/</creativeCommons:license>
        @else
        <copyright>{!! $show->itunes_copyright !!}</copyright>
        @endif

        <itunes:subtitle>{!! htmlspecialchars(substr($show->itunes_show_subtitle, 0, 255)) !!}</itunes:subtitle>

        <image>
        <title>{!! htmlspecialchars($show->title) !!}</title>
        <url>{!! $show->itunes_show_artwork_url !!}</url>
        <link>{!! $show->itunes_show_url !!}</link>
        </image>

        {{{ $categorytags->getItunescategoryTitlesByShowId($show->id) }}}


        {{-- Individual episodes --}}

        {{-- Assuming that there are no comments for the podcasts --}}

        @foreach ($episodes as $episode)

        <item>
            <title>{!! htmlspecialchars($episode->itunes_title) !!}</title>

            <link>{{{ Config::get('app.url') }}}/{!! $episode->slug !!}</link>

            <comments>
                {{{ Config::get('app.url') }}}/{!! $episode->slug !!}#respond
            </comments>

            <pubDate>{!! $episode->publish_date_formatted !!}</pubDate>

            <dc:creator>
            <![CDATA[ {!! $episode->itunes_author_name !!} ]]>
            </dc:creator>

            <guid isPermaLink="false">{{{ Config::get('app.url') }}}/{!! $episode->slug !!}</guid>

            <description>
            <![CDATA[ {!! htmlspecialchars($episode->itunes_description) !!} ]]>
            </description>

            <wfw:commentRss>
            @if (!empty($episode->commentrss_url))
                    {!! $episode->commentrss_url !!}
            @else
                    {{{ Config::get('app.url') }}}/{!! $episode->slug !!}/feed
            @endif
            </wfw:commentRss>

            <slash:comments>0</slash:comments>

            <enclosure url="{!! $show->media_file_storage_url !!}/{!! $episode->podcast_file_name !!}" length="{!! $episode->itunes_file_size !!}" type="audio/mpeg"/>

            <itunes:subtitle>{!! htmlspecialchars(substr($episode->itunes_subtitle, 0, 255)) !!}</itunes:subtitle>
            <itunes:summary>{!! htmlspecialchars($episode->itunes_summary) !!}</itunes:summary>
            <itunes:author>{!! $episode->itunes_author_name !!}</itunes:author>
            <itunes:explicit>{!! $episode->itunes_explicit_title !!}</itunes:explicit>
            <itunes:duration>{!! $episode->itunes_duration !!}</itunes:duration>

        </item>

        @endforeach

    </channel>
</rss>