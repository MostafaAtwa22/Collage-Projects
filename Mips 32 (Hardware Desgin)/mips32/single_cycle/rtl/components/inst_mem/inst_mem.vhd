library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
use work.inst_pck.all;
entity inst_mem is
generic(
    ADR_WIDTH : integer := 32;
    INSTRUCTION_WIDTH : integer := 32
);
port(
    read_add : in std_logic_vector(ADR_WIDTH-1 downto 0);
    inst : out std_logic_vector(INSTRUCTION_WIDTH-1 downto 0)
);
end entity;

architecture rtl of inst_mem is
    type ram_type is array(0 to 33) of std_logic_vector(31 downto 0); -- <16(changeable)> 4_byte word ram
    signal ram : ram_type := (
        i_inst(addi,s0,zero,0),     -- i = 0;
        i_inst(addi,s1,zero,0),     -- j = 0;
        i_inst(addi,s2,zero,6 * 4),     -- n = 6;         
        i_inst(addi,s3,zero,4),     -- pointer a = 4;
        i_inst(addi,t0,zero,20),    -- tmp = 20;                     
        r_inst(sltr,t1,s0,s2),       -- L2 : i < n => tmp1 = 1;
        i_inst(beq,zero,t1,5),     -- tmp1 == 0 => goto L1 : offcet 5;
        r_inst(addr,s4,s3,s0),      -- b = a+i;
        i_inst(sw,t0,s4,0),         -- *b = tmp;
        i_inst(subi,t0,t0,1),       -- tmp = tmp-1;
        i_inst(addi,s0,s0,1*4),       -- i = i+1;
        j_inst(j,5),               -- goto L2 : index 5;
        i_inst(addi,s0,zero,0),     -- L1 : i = 0;
        r_inst(sltr,t1,s0,s2),       -- L7 : i < n => tmp1 = 1;
        i_inst(beq,zero,t1,18),     -- tmp1 == 0 => goto L3 : offcet 18;
        i_inst(addi,s1,s0,1 * 4),       -- j = i+1;
        r_inst(sltr,t1,s1,s2),       -- L6 j < n => tmp1 = 1;
        i_inst(beq,zero,t1,13),     -- tmp1 == 0 => goto L4 : offcet 13;
        r_inst(addr,t2,s3,s1),      -- tmp2 = a + j;
        r_inst(addr,t3,s3,s0),      -- tmp3 = a + i;
        i_inst(lw,s4,t2,0),         -- b = *tmp2;
        i_inst(lw,s5,t3,0),         -- c = *tmp3;
        r_inst(sltr,t1,s4,s5),       -- b < c => tmp1 = 1;
        i_inst(beq,zero,t1,5),      -- tmp1 == 0 => goto L5 : offcet 5;
        r_inst(addr,t0,zero,s4),    -- tmp = b;
        r_inst(addr,s4,zero,s5),    -- b = c;
        r_inst(addr,s5,zero,t0),    -- c = tmp;
        i_inst(sw,s4,t2,0),         -- *tmp2 = b;
        i_inst(sw,s5,t3,0),         -- *tmp3 = c;
        i_inst(addi,s1,s1,1 * 4),       -- L5 : j = j+1;
        j_inst(j,16),               -- goto L6 : index 16;
        i_inst(addi,s0,s0,1 * 4),       -- L4 : i = i+1;
        j_inst(j,13),               -- goto L7 : index 13;
        j_inst(j,33)                -- L3 : goto L3 :index 33;
    );
    begin
        process(read_add) is
        begin
            if(read_add < std_logic_vector(to_unsigned(136,ADR_WIDTH))) then
                inst <= ram(to_integer(unsigned(read_add))/4);
            else
                inst <= (others => '0');
            end if;
        end process;
end architecture;

