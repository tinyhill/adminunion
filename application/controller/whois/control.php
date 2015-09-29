<?php

/**
 * 根据域名查询 Whois 信息
 */
function get_whois ($d) {

	//如果是 com 和 net 域名，使用 findname 工具
	if (preg_match('/\.((com)|(net))$/', $d)) {

		$domain = $d;
		require_once ('findname/whois.php');

	} else {

		require_once ('phpwhois/whois.main.php');
		require_once ('phpwhois/whois.utils.php');

		$whois = new Whois();
		$allowproxy = false;
		$result = $whois->Lookup($d);
		$rawdata = implode($result['rawdata'], '<br>');

		return $rawdata;

	}

}

/**
 * 查询域名是否可注册
 */
function get_availability ($d, $tld) {

	require_once('enom/class.EnomService.php');

	$d = preg_replace('/(\.((com)|(net)|(org)|(gov)|(gen)|(firm)|(ind)|(co)|(or)|(cn)|(br)|(de)|(eu)|(gb)|(hu)|(jpn)|(kr)|(no)|(qc)|(ru)|(sa)|(se)|(uk)|(us)|(uy)|(za)|(ac)|(bj)|(sh)|(cq)|(he)|(sx)|(nm)|(ln)|(jl)|(hl)|(js)|(zj)|(ah)|(fj)|(jx)|(sd)|(ha)|(hb)|(hn)|(gd)|(gx)|(hi)|(sc)|(gz)|(yn)|(xz)|(sn)|(gs)|(qh)|(nx)|(xj)|(tj)|(tw)|(hk)|(mo)))?\.((aero)|(asia)|(biz)|(cat)|(com)|(coop)|(edu)|(gov)|(info)|(int)|(jobs)|(mil)|(mobi)|(museum)|(name)|(net)|(org)|(pro)|(tel)|(travel)|(xxx)|(ac)|(ad)|(ae)|(af)|(ag)|(ai)|(al)|(am)|(an)|(ao)|(aq)|(ar)|(as)|(at)|(au)|(aw)|(az)|(ax)|(ba)|(bb)|(bd)|(be)|(bf)|(bg)|(bh)|(bi)|(bj)|(bm)|(bn)|(bo)|(br)|(bs)|(bt)|(bv)|(bw)|(by)|(bz)|(ca)|(cc)|(cd)|(cf)|(cg)|(ch)|(ci)|(ck)|(cl)|(cm)|(cn)|(co)|(cr)|(cs)|(cu)|(cv)|(cx)|(cy)|(cz)|(de)|(dj)|(dk)|(dm)|(do)|(dz)|(ec)|(ee)|(eg)|(eh)|(er)|(es)|(et)|(eu)|(fi)|(fj)|(fk)|(fm)|(fo)|(fr)|(ga)|(gb)|(gd)|(ge)|(gf)|(gg)|(gh)|(gi)|(gl)|(gm)|(gn)|(gp)|(gq)|(gr)|(gs)|(gt)|(gu)|(gw)|(gy)|(hk)|(hm)|(hn)|(hr)|(ht)|(hu)|(id)|(ie)|(il)|(im)|(in)|(io)|(iq)|(ir)|(is)|(it)|(je)|(jm)|(jo)|(jp)|(ke)|(kg)|(kh)|(ki)|(km)|(kn)|(kp)|(kr)|(kw)|(ky)|(kz)|(la)|(lb)|(lc)|(li)|(lk)|(lr)|(ls)|(lt)|(lu)|(lv)|(ly)|(ma)|(mc)|(md)|(me)|(mg)|(mh)|(mk)|(ml)|(mm)|(mn)|(mo)|(mp)|(mq)|(mr)|(ms)|(mt)|(mu)|(mv)|(mw)|(mx)|(my)|(mz)|(na)|(nc)|(ne)|(nf)|(ng)|(ni)|(nl)|(no)|(np)|(nr)|(nu)|(nz)|(om)|(pa)|(pe)|(pf)|(pg)|(ph)|(pk)|(pl)|(pm)|(pn)|(pr)|(ps)|(pt)|(pw)|(py)|(qa)|(re)|(ro)|(ru)|(rw)|(sa)|(sb)|(sc)|(sd)|(se)|(sg)|(sh)|(si)|(sj)|(sk)|(sl)|(sm)|(sn)|(so)|(sr)|(st)|(sv)|(sy)|(sz)|(tc)|(td)|(tf)|(tg)|(th)|(tj)|(tk)|(tl)|(tm)|(tn)|(to)|(tp)|(tr)|(tt)|(tv)|(tw)|(tz)|(ua)|(ug)|(uk)|(um)|(us)|(uy)|(uz)|(va)|(vc)|(ve)|(vg)|(vi)|(vn)|(vu)|(wf)|(ws)|(ye)|(yt)|(yu)|(za)|(zm)|(zw))$/', '', $d);
	$d_arr = explode('.', $d);
	$d = $d_arr[sizeof($d_arr) - 1];

	$enom = new EnomService('mangguo', '19870930', false, true);
	$availability = $enom->checkDomain($d, $tld, true);

	$status = $availability[$d.'.'.$tld] ? 'true' : 'false';

	return '{"domain":"' . $d . '.' . $tld . '","status":' . $status . '}';

}

?>