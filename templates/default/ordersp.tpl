<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<META name="HandheldFriendly" content="True">
	{head}
	</head>
	<body>
		{scripts}
		<center>
		{people_number}
		{messages}

		<table>
		<tr>
<!--				<td rowspan="4" valign="top">
					{toplist}
				</td>-->
				<td>
					<table>
					<tr>
						<td valign="top">{vertical_navbar}</td>
						<td valign="top">{toplist}</td>
						<td valign="top">{categories}</td>
						<td valign="top">{letters}</td>
					</tr>
					</table>
				</td>
				<td align="left" valign="top" rowspan="4">
					<table><!--<tr><td>
					{last_order}
					</td></tr>-->
					<tr><td>
					{orders_list}
					&nbsp;
					</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					{fast_order_id}
				</td>
			</tr>
<!--			<tr>
				<td>
					{toplist}
				</td>
			</tr>-->
			<tr>
				<td>
					{commands}
				</td>
			</tr>
		</table>
		{logout}
		</center>
	</body>
</html>
